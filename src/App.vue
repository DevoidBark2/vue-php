<template>
  <div class="app">
    <div class="image-block">
      <img src="./assets/image.jpeg" alt="image" class="image"/>
    </div>
    <form method="POST" class="form"> 

<input type="text" name="user_name" v-model="formControl.user_name" placeholder="Имя..." class="name_input"/>
<textarea name="comment" v-model="formControl.comment" placeholder="Комментарий..." class="comment_input"/>

<div class="captcha-area">
  <div class="captcha-img">
    <img src="./assets/bgcaptcha.png" alt="captcha-bg"/>
    <span class="captcha">{{ imageCaptha }}</span>
  </div>
  <input type="text" v-model="inputCaptha" placeholder="Введите капчу..."/>
</div>

<div class="btn-block">
  <button class="submit-btn" @click.prevent="submitComment()">Добавить комментарий</button>
</div>

</form>
<div>
        <h2>Все комментарии</h2>
        <div v-if="comments?.length > 0">
            <div v-for="comment in comments" :key="comment.id">
                <div class="comment">
                    <div class="left-block">
                        <p>{{ comment.user_name }}</p>
                        <p>{{ comment.comment }}</p>
                    </div>
                    <div class="right-block">
                        <p>{{ comment.date }}</p>
                        <button @click="deleteComment(comment.id)">Удалить комментарий</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            Комментариев нет
        </div>
  </div>

  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'App',
  data(){
    return{
      formControl:{
        user_name:"",
        comment:""
      },
      comments:[],
      imageCaptha:null,
      inputCaptha:null,
      allCharacters:['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T',
                    'U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n',
                    'o','p','q','r','s','t','u','v','w','x','y','z',0,1,2,3,4,5,6,7,8,9]
    } 
  },
  created(){
    this.getComments();
    this.getCaptcha();
  },
  methods:{
    submitComment(){
        if((this.formControl.user_name == null || this.formControl.user_name == '') 
            || (this.formControl.comment == null || this.formControl.comment == '')){
          alert('Введите данные!')
        }
        else if(this.imageCaptha !== this.inputCaptha){
          alert('Неверный ввод капчи')
        }
        else{
          let data = new FormData();
          data.append('user_name',this.formControl.user_name);
          data.append('comment',this.formControl.comment);
          axios.post("http://vue-php/src/api/api.php?action=addcomment",data).then(res=>{
            alert(res.data.message)
          }).catch(err => console.log('Ошибка при добавлении комментария',err))
          this.getComments();
          this.formControl.user_name="";
          this.formControl.comment="";
          this.inputCaptha = "";
          this.getCaptcha();
      }
    },
    getComments(){
      axios.get("http://vue-php/src/api/api.php?action=getcomments").then(res=>{
        this.comments = res.data.comment_data
      }).catch(e =>{
        console.log('Ошибка получения данных',e)
      })
    },
    deleteComment(id){
      axios.post('http://vue-php/src/api/api.php?action=deletecomment&id='+ id +'',{
        action:"deletecomment"
      }).then(res =>{
        alert(res.data.message);
        this.getComments();
      })
    },
    getCaptcha(){
      this.imageCaptha = ''
      for(let i=0;i < 6;i++){
        let randomCaptcha = this.allCharacters[Math.floor(Math.random() * this.allCharacters.length)];
        this.imageCaptha+=randomCaptcha;
      }
    }
  }
}
</script>



<style>
*{
  margin: 0;
  padding: 0;
}
.app{
  max-width: 900px;
  margin: 0 auto;
}
.image-block{
  text-align: center;
}
.image{
  width: 500px;
  height: 300px;
}
.form{
  display: flex;
  flex-direction: column;
  border: 1px solid teal;
  padding: 10px;
}
.name_input{
  width: 200px;
  border: 1px solid teal;
  margin-bottom: 15px;
  padding: 5px;
}
.name_input:focus{
  outline: none;
  border: 1px solid teal;
}
.comment_input{
  height: 100px;
  resize: none;
  border: 1px solid teal;
  padding: 5px;
}
.comment_input:focus{
  outline: none;
}
.btn-block{
  display: flex;
  align-self: flex-end;
}
.submit-btn{
  background: none;
  border: 1px solid teal;
  padding: 10px;
  transition: all 0.5s ease;
}
.submit-btn:hover{
  background: teal;
  color:white;
  cursor: pointer;
}
.comment{
  display: flex;
  justify-content: space-between;
  border: 1px solid black;
  padding: 10px;
}

.left-block{
  display: flex;
  flex-direction: column;
}
.right-block{
  display: flex;
  flex-direction: column;
}

.captcha-img{
  position: relative;
  width:100%;
}
.captcha{
  position: absolute;
  left: 7%;
  top: 30px;
  color:rgb(37, 37, 37);
  font-size: 35px;
}
</style>
