@if($data->emailRecipients->count() > 0)
   <ul>
       @foreach($data->emailRecipients as $email_address)
           <li>{{$email_address->email_address}}</li>
       @endforeach
   </ul>
@endif