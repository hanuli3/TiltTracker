import { Component } from '@angular/core';
import { Order } from './order';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'CS4640 - inclass12';
  drinks = ['Coffee', 'Tea', 'Milk'];
  orderModel = new Order('', 'duh@uva.edu', 9991234567, '', '', true);
}
