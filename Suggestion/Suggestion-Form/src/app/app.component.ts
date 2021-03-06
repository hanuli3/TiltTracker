//author David Xue
import { Component } from '@angular/core';
import { Order } from './order';
import { HttpClient, HttpErrorResponse, HttpParams } from '@angular/common/http';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'TiltTracker Suggestion Form';
  drinks = ['General', 'Technical', 'Account'];
  orderModel = new Order('', '', null, '', '', false);

  responsedata = 'response data';

  constructor(private http: HttpClient) { }
   
  senddata(data) {
   console.log(data);

   let params = JSON.stringify(data);

   this.http.get('http://localhost/TiltTracker/ngphp-get.php?str='+params)
   .subscribe((data) => {
      console.log('Got data from backend', data);
      this.responsedata = data.toString();
   }, (error) => {
      console.log('Error', error);
   })
}
}
