import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-tagsinput',
  templateUrl: './tagsinput.component.html',
  styleUrls: ['./tagsinput.component.scss']
})
export class TagsinputComponent implements OnInit {

  constructor() { }

  tags = ['Обращение', 'Интернет'];
  newTag = '';

  ngOnInit(): void {
  }

  addTag ()
  {
    this.tags.push(this.newTag);
    this.newTag = '';
  }
}
