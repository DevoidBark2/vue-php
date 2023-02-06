<template>
  <div class="app">
    <div class="image-block">
      <img src="./assets/image.jpeg" alt="image" class="image"/>
    </div>
    <!-- Форма добавления комментария -->
    <form method="POST" class="form"> 
        <input type="text" name="user_name" v-model="formControl.user_name" placeholder="Имя..." class="input name_input"/>
        <textarea name="comment" v-model="formControl.comment" placeholder="Текст комментария..." class="input comment_input"/>
        <div>
            <div class="captcha-img">
                <img src="./assets/bgcaptcha.png" alt="captcha-bg"/>
                <span class="captcha">{{ imageCaptha }}</span>
            </div>
            <input class="input" type="text" v-model="inputCaptha" placeholder="Введите капчу..."/>
        </div>
        <div class="btn-block">
            <button class="btn submit-btn" @click.prevent="submitComment()">Добавить комментарий</button>
        </div>
    </form>
      <!-- Вывод комментариев -->
    <div class="comment-block">
        <h1 class="title">Все комментарии</h1>
        <div v-if="comments?.length > 0">
                <div class="comment" v-for="comment in comments" :key="comment.id">
                    <div class="comments-block">
                        <p class="comment-user">{{ comment.user_name }}</p>
                        <div class="comment-content">
                          <p>{{ comment.comment }}</p>
                        </div>
                    </div>
                    <div class="comments-block right-block">
                        <p>{{ comment.date }}</p>
                        <button class="btn delete-btn" @click="deleteComment(comment.id)">Удалить комментарий</button>
                    </div>
                </div>
        </div>
        <div v-else class="not-comment">
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
          axios.post("http://vue-php/src/api/api.php?action=addcomment",data).then(res =>{
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
      axios.get("http://vue-php/src/api/api.php?action=getcomments").then(res =>{
        this.comments = res.data.comment_data
      }).catch(e => console.log('Ошибка получения данных',e))
    },
    deleteComment(id){
      axios.post('http://vue-php/src/api/api.php?action=deletecomment&id='+ id +'').then(res =>{
        alert(res.data.message);
      }).catch(e =>console.log('Ошибка удаления комментария',e))
      this.getComments();
    },
    getCaptcha(){
      this.imageCaptha = ''
      for(let i=0;i < 6;i++){
        let randomCaptcha = this.allCharacters[Math.floor(Math.random() * this.allCharacters.length)];
        this.imageCaptha += randomCaptcha;
      }
    }
  }
}
</script>
<style>
/* Main style */
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
/* Form style */
.form{
  display: flex;
  flex-direction: column;
  gap:20px;
  border: 1px solid teal;
  padding: 10px;
}
.input{
  border: 1px solid teal;
  padding: 5px;
  outline: none;
}
.name_input{
  width: 200px;
}
.comment_input{
  height: 100px;
  resize: none;
}
.btn-block{
  display: flex;
  align-self: flex-end;
}
.btn{
  background: none;
  padding: 10px;
  transition: all 0.5s ease;
}
.btn:hover{
  color:white;
  cursor: pointer;
}
.submit-btn{
  border: 1px solid teal;
}
.submit-btn:hover{
  background: teal;
}
.captcha-img{
  position: relative;
}
.captcha{
  background: none;
  position: absolute;
  left: 7%;
  top: 30px;
  color:rgb(37, 37, 37);
  font-size: 35px;
}
.delete-btn{
  border: 1px solid firebrick;
}
.delete-btn:hover{
  background: firebrick;
}
/* Блок комментарии */
.title{
  text-align: center;
}
.comment-block{
  margin: 20px 0;
}
.comment{
  display: flex;
  justify-content: space-between;
  align-items: center;
  border: 1px solid black;
  padding: 15px;
  margin-top: 10px;
}
.comment-user{
  font-weight: 900;
  font-size: 20px;
}
.comment-content{
  width: 700px;
  word-break: break-all;
}
.comments-block{
  display: flex;
  flex-direction: column;
  gap:15px;
}
.right-block{
  align-items: flex-end;
}
.not-comment{
  text-align: center;
}
</style>
