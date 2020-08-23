import {Component, Input, OnInit} from '@angular/core';

@Component({
  selector: 'app-textinput',
  templateUrl: './textinput.component.html',
  styleUrls: ['./textinput.component.scss']
})
export class TextinputComponent implements OnInit {

  @Input() text;

  constructor() { }

  ngOnInit(): void {
  }

}
