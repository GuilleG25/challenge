<?php
    
    namespace App\Http\Controllers\Api;
    
    use App\DeathAppointment;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Validation\Rule;
    
    
    class ApiController extends Controller {
        
        public function __construct() {
        
        }
        
        public function index() {
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => __('Api 1.0'),
            ];
            
            return response()->json($response, 200);
        }
        
        public function store(Request $request) {
            $validator = Validator::make($request->all(), [
                'date'  => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $value = strtotime($value);
                        $value = date('w', $value);
                        
                        return $value > 0 && $value < 6 ? true : $fail($attribute . ' its not a business day');
                    },
                ],
                'time'  => [
                    'required',
                    'regex:/(09|1[0-9]):00:00/',
                    'unique_custom:death_appointment,date,' . $request->get('date'),
                ],
                'name'  => 'required',
                'email' => 'nullable|required|email|unique:death_appointment',
            ], $this->custom_message());
            
            if ($validator->fails()) {
                $response = [
                    'status'  => 'FAILED',
                    'code'    => 400,
                    'message' => 'Incorrect parameters',
                    'data'    => $validator->errors()->getMessages(),
                ];
                
                return response()->json($response, 400);
            }
            
            
            $value        = new DeathAppointment;
            $value->date  = $request->get('date');
            $value->time  = $request->get('time');
            $value->name  = $request->get('name');
            $value->email = $request->get('email');
            $value->save();
            
            $response = [
                'status'  => 'OK',
                'code'    => 200,
                'message' => 'Appointment created correctly',
            ];
            
            return response()->json($response, 200);
        }
        
        public function date($date) {
            
            $data = DeathAppointment::select('time')->where('date', $date)->orderBy('time', 'asc')->get();
            
            $response = [
                'status' => 'OK',
                'code'   => 200,
                'data'   => $data,
            ];
            
            return response()->json($response, 200);
        }
        
        public function custom_message() {
            
            return [
                'time.regex'         => 'Death only attends from 9am to 7pm.',
                'email.unique'       => 'Your date with death is over',
                'time.unique_custom' => 'Select another date, this one is already taken',
            ];
        }
        
        
    }
