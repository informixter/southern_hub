import { Component, OnInit } from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {MainService} from "../main.service";

@Component({
  selector: 'app-configure',
  templateUrl: './configure.component.html',
  styleUrls: ['./configure.component.scss']
})
export class ConfigureComponent implements OnInit {

  constructor(private route : ActivatedRoute, private service : MainService) { }

  text : any = null;
  loading = false;
  tags = [];
  idModel;
  saving = false;


  ngOnInit(): void {
    this.loading = true;
    this.service.getTextById(this.route.snapshot.params.idText).subscribe((response: any) =>
    {
      this.text = response;
      this.loading = false;
    });

    this.idModel = this.route.snapshot.params.idModel;
    this.service.getLabels(this.route.snapshot.params.idModel).subscribe((response: any) =>
    {
      console.log(response);
      this.tags = response.map(tag => ({...tag}));
      this.loading = false;
    });
  }

  save ()
  {
    this.saving = true;
    this.service.saveLabelsForText(this.route.snapshot.params.idText, this.text.tags.map(tag => tag.id || tag.model_id)).subscribe(response =>
    {
      this.saving = false;
    });

    this.service.editText(this.route.snapshot.params.idText, this.text.text).subscribe(response =>
    {

    });

  }

  set (text)
  {
    console.log(text.tags.map(tag => tag.id || tag.model_id));
    this.text = text;
  }
}
