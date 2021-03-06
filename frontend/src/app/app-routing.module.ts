import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {ConfigureComponent} from "./configure/configure.component";
import {TextsComponent} from "./texts/texts.component";
import {ModelsComponent} from "./models/models.component";
import {UserInputComponent} from "./user-input/user-input.component";
import {TestComponent} from "./test/test.component";

const routes: Routes = [
  {
    path : 'models',
    component : ModelsComponent
  },
  {
    path : 'text-detail/:idModel/:idText',
    component : ConfigureComponent
  },
  {
    path : 'model-detail/:idModel',
    component : TextsComponent
  },
  {
    path : 'user',
    component : UserInputComponent
  },
  {
    path : 'test/:idModel',
    component : TestComponent
  },
  {
    path : '**',
    redirectTo : 'models'
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
