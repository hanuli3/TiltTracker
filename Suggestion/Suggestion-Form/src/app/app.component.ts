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
  title = 'CS4640 Suggestion Form';
  drinks = ['General', 'Technical', 'Account'];
  orderModel = new Order('', '', null, '', '', false);

  responsedata = 'response data';

  constructor(private http: HttpClient) { }
   
  senddata(data) {
     console.log(data);

     let params = JSON.stringify(data);

     //this.http.get('http://localhost/cs4640s19/ngphp-get.php?str='+encodeURIComponent(params))
     this.http.get('http://localhost/TiltTracker/ngphp-get.php?str='+params)
     //this.http.post('http://localhost/cs4640s19/ngphp-post.php', data)
     .subscribe((data) => {
        console.log('Got data from backend', data);
        this.responsedata = data.toString();
     }, (error) => {
        console.log('Error', error);
     })
  }
}
