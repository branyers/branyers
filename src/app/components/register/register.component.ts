import { Component, OnInit } from '@angular/core';
import {User} from "../../models/user";
import {UserService} from '../../services/user.service';

@Component({
  selector: 'register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css'],
  providers : [UserService]
})
export class RegisterComponent implements OnInit {
  public page_title:string
  public user: User;
  public status: string;
  constructor(private _userservice: UserService) { 
    this.page_title = "Registrate"
     this.user = new User(1,'','','ROLE_USER','','','','');
}

  
  ngOnInit() {
    console.log("Componente de registro lanzado!!");
    console.log(this._userservice.test());
  }


  onSubmit(form){
   this._userservice.register(this.user).subscribe(
     Response => {
       console.log(Response);
       if(Response.status == "success"){
         this.status = Response.status;
         form.reset();
       }else{
         this.status = "error";
       }
      
    },
    error =>{
      this.status = "error";
      console.log(<any>error);
    }
   );
  
  }

}

