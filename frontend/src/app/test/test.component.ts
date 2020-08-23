import { Component, OnInit } from '@angular/core';
import {AnotherService} from "../another.service";
import {ActivatedRoute} from "@angular/router";

@Component({
  selector: 'app-test',
  templateUrl: './test.component.html',
  styleUrls: ['./test.component.scss']
})
export class TestComponent implements OnInit {

  constructor(private zalupaService : AnotherService, private route : ActivatedRoute) { }

  ngOnInit(): void {
  }

  text = '';
  result : any = '';
  loading = false;

  go ()
  {
    this.loading = true;
    this.zalupaService.testRequest(this.route.snapshot.params.idModel, this.text).subscribe(response =>
    {
      this.result = response;
      this.loading = false;
    })
  }
}
