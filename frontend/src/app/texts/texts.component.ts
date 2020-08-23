import { Component, OnInit } from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {MainService} from "../main.service";
import {AnotherService} from "../another.service";

@Component({
  selector: 'app-texts',
  templateUrl: './texts.component.html',
  styleUrls: ['./texts.component.scss']
})
export class TextsComponent implements OnInit {

  constructor(private router : Router, private service : MainService, private route : ActivatedRoute, private zalupaService : AnotherService) { }

  model : any = null;
  texts = [];

  loading = false;
  saving = false;
  info = '';

  ngOnInit(): void {
    this.loading = true;
    this.service.getTexts(this.route.snapshot.params.idModel).subscribe((response: any) =>
    {
      this.texts = response.texts;
      this.model = response.model;
      this.loading = false;
    });
  }

  getTextPreview (text)
  {
    if (text.length > 200)
    {
      return text.slice(0, 200) + '...';
    }

    return text;
  }

  open (text)
  {
    this.router.navigate(['/text-detail', this.model.id, text.id]);
  }

  add ()
  {
    let text = prompt('Введите текст');
    if (!text)
    {
      return;
    }
    this.saving = true;
    this.service.addText(this.route.snapshot.params.idModel, text).subscribe((response: any) =>
    {
      this.texts.unshift(response);
      this.saving = false;
    });
  }

  testRequest ()
  {
    this.zalupaService.testRequest(this.route.snapshot.params.idModel).subscribe(response =>
    {
      alert(JSON.stringify(response));
    });
  }

  getInfo ()
  {
    this.zalupaService.info(this.route.snapshot.params.idModel).subscribe(response =>
    {
      this.info = JSON.stringify(response);
    });
  }

  run ()
  {
      this.zalupaService.run(this.route.snapshot.params.idModel).subscribe(response =>
      {
        alert(JSON.stringify(response));
      });
  }
}
