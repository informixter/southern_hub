import { Component, OnInit } from '@angular/core';
import {MainService} from "../main.service";

@Component({
  selector: 'app-user-input',
  templateUrl: './user-input.component.html',
  styleUrls: ['./user-input.component.scss']
})
export class UserInputComponent implements OnInit {

  constructor(private service : MainService) { }

  input = '';
  loading = false;
  result : any = [];
  ngOnInit(): void {

  }

  onChange ()
  {
    this.loading = true;
    this.service.search(this.input).subscribe(response =>
    {
      this.result = response;
      this.loading = false;
    });
  }
}
