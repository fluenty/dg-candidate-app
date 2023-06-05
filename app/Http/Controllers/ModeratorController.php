<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Validator;
use Hashids;
use Storage;
use DB;

use App\ClientModerator;
use App\ModeratorFile;
use App\Client;
use App\Title;
use App\User;
use App\File;

class ModeratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($encodedClientId=null)
    {
        $decodedClientId = Hashids::decode($encodedClientId);
        $clients = Client::orderBy('title', 'ASC')->get();
        $moderatorsQuery = User::where('user_type_id', 2);
        if($encodedClientId){
            $moderatorsQuery->join('client_moderators', 'client_moderators.moderator_id' ,'=' ,'users.id');
            $moderatorsQuery->where('client_moderators.client_id', $decodedClientId);
        }
        $moderators = $moderatorsQuery->get();

        return view('moderators.index', [
            'clients' => $clients,
            'moderators' => $moderators,
            'encodedClientId' => $encodedClientId
        ]);
    }

    public function create()
    {
        $clients = Client::orderBy('title', 'ASC')->get();
        $titles = Title::orderBy('id', 'ASC')->get();

        return view('moderators.create', compact(
            'clients',
            'titles'
        ));
    }

    public function store(Request $request)
    {
        $decodedTitleId = Hashids::decode($request->title_id)[0];

        $rules = [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ];

        $messages = [
            'password.regex' => 'Password needs at least an uppercase, lowercase, number and special character'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please make sure the fields have been filled in correctly']);
        }

        if($request->avatar){

            $extension = $request->avatar->getClientOriginalExtension();
            $filename  = 'avatar-'.time().'.'.$extension;

            $avatar = new File;
            $avatar->name = $request->avatar->storeAs('/', $filename);
            $avatar->extension = $extension;
            $avatar->mime = $request->avatar->getClientMimeType();
            $avatar->size = $request->avatar->getSize();
            $avatar->save();

        }

        $moderator = new User;
        $moderator->user_type_id = 2;
        $moderator->title_id = $decodedTitleId;
        $moderator->name = $request->name;
        $moderator->surname = $request->surname;
        $moderator->email = $request->email;
        $moderator->cellphone = $request->cellphone;
        $moderator->password = bcrypt($request->password);
        isset($avatar) ? $moderator->avatar_id = $avatar->id : null;
        $moderator->save();

        ClientModerator::where('moderator_id', $moderator->id)->delete();
        if($request->clients){
            $data = [];

            foreach($request->clients as $clients)
            {
                $client_id = Hashids::decode($clients)[0];
                $data[] = [
                    'client_id' => $client_id,
                    'moderator_id' => $moderator->id
                ];
            }
            ClientModerator::insert($data);
        }

        return redirect()->route('moderator.index')->with(['success' => 'Moderator successfully created.']);
    }

    public function edit($id)
    {
        $decodedId = Hashids::decode($id);

        $moderator = User::where('id', $decodedId)->first();

        $avatar = File::where('id', $moderator->avatar_id)->first();

        $client_ids = Client::join('client_moderators', 'client_moderators.client_id', '=', 'clients.id')
            ->where('moderator_id', $decodedId)
            ->pluck('client_id')->toArray();

        $clients = Client::orderBy('title', 'ASC')->get();
        $titles = Title::orderBy('id', 'ASC')->get();

        return view('moderators.edit', compact(
            'moderator',
            'clients',
            'client_ids',
            'id',
            'avatar',
            'titles'
        ));
    }

    public function update(Request $request)
    {

        $decodedId = Hashids::decode($request->id)[0];
        $decodedTitleId = Hashids::decode($request->title_id)[0];

        $rules = [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|unique:users,email,'.$decodedId
        ];

        if($request->password != '')
        {
            $rules['password'] = 'min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/';
        }

        $messages = [
            'password.regex' => 'Password needs at least 1 uppercase, 1 lowercase, 1 number and 1 special character.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please make sure the fields have been filled in correctly']);
        }

        if($request->avatar){
            $extension = $request->avatar->getClientOriginalExtension();
            $filename  = 'avatar-'.time().'.'.$extension;

            $avatar = new File;
            $avatar->name = $request->avatar->storeAs('/', $filename);
            $avatar->extension = $extension;
            $avatar->mime = $request->avatar->getClientMimeType();
            $avatar->size = $request->avatar->getSize();
            $avatar->save();

        }

        $moderator = User::where('id', $decodedId)->first();
        $moderator->title_id = $decodedTitleId;
        $moderator->name = $request->name;
        $moderator->surname = $request->surname;
        $moderator->email = $request->email;
        $moderator->cellphone = $request->cellphone;
        ($request->password != '') ? ($moderator->password = bcrypt($request->password)) : '';
        isset($avatar) ? $moderator->avatar_id = $avatar->id : null;
        $moderator->save();

        ClientModerator::where('moderator_id', $decodedId)->delete();
        if($request->clients){
            $data = [];

            foreach($request->clients as $clients)
            {
                $client_id = Hashids::decode($clients)[0];
                $data[] = [
                    'client_id' => $client_id,
                    'moderator_id' => $decodedId
                ];
            }
            ClientModerator::insert($data);
        }

        return redirect()->route('moderator.index')->with(['success' => 'Moderator successfully updated.']);
    }

    public function destroy($id)
    {
        $decodedId = Hashids::decode($id);

        User::where('id', $decodedId)->delete();

        return [
            'success' => true,
            'message' => 'Moderator successfully deleted.'
        ];
    }

    public function destroyAvatar($id)
    {
        $decodedId = Hashids::decode($id);

        $moderator = User::where('id', $decodedId)->first();

        $file = File::where('id', $moderator->avatar_id)->first();

        $moderator->avatar_id = null;
        $moderator->save();
        Storage::delete($file->name);
        $file->delete();

        return [
            'success' => true,
            'message' => 'Profile Photo successfully deleted.'
        ];
    }

}
