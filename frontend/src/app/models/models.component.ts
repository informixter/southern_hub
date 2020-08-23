import { Component, OnInit } from '@angular/core';
import {Router} from "@angular/router";
import {MainService} from "../main.service";

@Component({
  selector: 'app-models',
  templateUrl: './models.component.html',
  styleUrls: ['./models.component.scss']
})
export class ModelsComponent implements OnInit {

  constructor(private router : Router, private service : MainService) { }

  models : any = [];
  loading = false;
  saving = false;

  ngOnInit(): void {
    this.loading = true;
    this.service.getModels().subscribe(response =>
    {
      this.models = response;
      this.loading = false;
    });
  }

  open (model)
  {
    this.router.navigate(['/model-detail', model.id]);
  }

  add ()
  {
    let text = prompt('Введите название модели');
    if (!text)
    {
      return;
    }
    this.saving = true;
    this.service.addModel(text).subscribe((response: any) =>
    {
      this.models.unshift(response);
      this.saving = false;
    });


  }

}
