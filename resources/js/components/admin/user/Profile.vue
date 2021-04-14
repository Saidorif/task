<template>
  <div class="profile">
    <div class="page_header">
      <h4 class="header_title">Менинг маълумотларим</h4>
    </div>
    <div class="jv_card">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a
            class="nav-link active"
            id="profile-tab"
            data-toggle="tab"
            href="#profile"
            role="tab"
            aria-controls="profile"
            aria-selected="false"
          >
            <i class="peIcon pe-7s-unlock"></i> Паролни ўзгартириш
          </a>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div
          class="tab-pane fade show active"
          id="profile"
          role="tabpanel"
          aria-labelledby="profile-tab"
        >
          <form @submit.prevent.enter="changePassword">
            <div class="profile_block">
              <div class="input_style">
                <input
                  type="text"
                  class="form-control input_style"
                  id="exampleInputPassword1"
                  v-model="passwords.password"
                  required
                  :class="isRequired(passwords.password) ? 'isRequired' : ''"
                />
                <label for="exampleInputPassword1">Парол</label>
              </div>
              <div class="input_style">
                <input
                  type="password"
                  class="form-control input_style"
                  id="ConfirmPassword1"
                  v-model="passwords.confirm_password"
                  :class="
                    isRequiredPassword(passwords.confirm_password)
                      ? 'isRequired'
                      : ''
                  "
                  @input="confirmPassword()"
                  required
                />
                <label for="ConfirmPassword1"> Паролни тасдиқлаш</label>
                <small class="redText" v-if="checkPassword">
                  <b>Парол мос емас</b>
                </small>
              </div>
                <button type="submit" class="btn_green">
                  <i class="sidebar_icon" data-feather="save"></i>
                  Сақлаш
                </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      form: {
        name: "",
        email: "",
        address: "",
        phone: "",
        image: "/img/user.jpg",
        file: "",
        text: "",
      },
      fileFormat: "нет-файла",
      passwords: {
        password: "",
        confirm_password: "",
      },
      requiredInput: false,
      requiredPassword: false,
      checkPassword: false,
    };
  },
  computed: {
    ...mapGetters("user", ["getProfile"]),
  },
  async mounted() {
    await this.ActionProfile();
    this.form = this.getProfile;
  },
  updated() {
    feather.replace();
  },
  methods: {
    ...mapActions("user", [
      "ActionProfile",
      "ActionProfileUpdate",
      "ActionChangePassword",
    ]),
    confirmPassword() {
      if (this.passwords.password && this.passwords.confirm_password) {
        if (this.passwords.password != this.passwords.confirm_password) {
          this.checkPassword = true;
        } else {
          this.checkPassword = false;
        }
      }
    },
    photoImg(img) {
      if (img) {
        if (img.length < 100) {
          return "/users/" + img;
        } else {
          return img;
        }
      }
    },
    changeFile(event) {
      let file = event.target.files[0];
      if (file.size > 1048576) {
        swal.fire({
          type: "error",
          icon: "error",
          title: "Ошибка",
          text: "Файл ҳажми 1МБ дан ошмаслиги керак",
        });
      } else {
        let reader = new FileReader();
        reader.onload = (event) => {
          this.form.file = event.target.result;
          this.fileFormat = "docx";
        };
        reader.readAsDataURL(file);
      }
    },
    changePhoto(event) {
      let file = event.target.files[0];
      if (
        event.target.files[0]["type"] === "image/png" ||
        event.target.files[0]["type"] === "image/jpeg" ||
        event.target.files[0]["type"] === "image/jpg"
      ) {
        if (file.size > 1048576) {
          swal.fire({
            type: "error",
            title: "Ошибка",
            text: "Расм ҳажми чегарадан каттароқ",
          });
        } else {
          let reader = new FileReader();
          reader.onload = (event) => {
            this.form.image = event.target.result;
          };
          reader.readAsDataURL(file);
        }
      } else {
        swal.fire({
          type: "error",
          title: "Ошибка",
          text: "Расм фақат png, jpg, jpeg бўлиши керак!",
        });
      }
    },
    isRequired(input) {
      return this.requiredInput && input === "";
    },
    isRequiredPassword(input) {
      return this.requiredPassword && input === "";
    },
    async sendProfile() {
      if (this.form.name && this.form.email) {
        await this.ActionProfileUpdate(this.form);
        toast.fire({
          type: "success",
          icon: "success",
          title: "Маълумот ўзгарди!",
        });
      } else {
        this.requiredInput = true;
      }
    },
    async changePassword() {
      if (
        this.passwords.password &&
        this.passwords.confirm_password &&
        this.checkPassword == false
      ) {
        await this.ActionChangePassword(this.passwords);
        toast.fire({
          type: "success",
          icon: "success",
          title: "Парол ўзгарди!",
        });
      } else {
        this.requiredPassword = true;
      }
    },
    printCv() {
      $(".cv_block").printThis();
    },
  },
};
</script>
<style scoped>
.cv_tab {
  background: #9fc1cc40;
  padding: 30px 0px;
}
page {
  background: white;
  display: block;
  margin: 0px auto;
  margin-bottom: 0.5cm;
}
page[size="A4"] {
  width: 21cm;
  height: 29.7cm;
  padding: 30px;
}
page[size="A4"][layout="landscape"] {
  width: 29.7cm;
  height: 21cm;
}
.cv_title {
  text-align: center;
  font-weight: bold;
}
.cv_user_img {
  width: 130px;
  height: 150px;
  overflow: hidden;
  border: 1px solid #000;
  margin-right: 30px;
  margin-bottom: 30px;
}
.cv_user_img img {
  width: 100%;
}
.cv_header {
  display: flex;
  align-items: flex-start;
}
.cv_header_info {
  width: calc(100% - 150px);
}
.cv_header_info h2 {
  font-weight: bold;
}
.cv_block p {
  font-size: 16px;
}
.cv_header_info p {
  margin-bottom: 0;
}
.cv_body_list {
  display: flex;
  flex-wrap: wrap;
}
.cv_body_list li {
  list-style: none;
  width: 60%;
  margin-bottom: 10px;
}
.cv_body_list li:nth-child(odd) {
  width: 40%;
}
.cv_body_list li {
  display: flex;
  flex-direction: column;
}
.cv_body_list li:last-child {
  width: 100%;
}
.cv_exper_subtitle {
  text-align: center;
  font-weight: bold;
}
.cv_experience_list {
}
.cv_experience_list li {
  list-style: none;
  display: flex;
  justify-content: space-between;
}
.cv_ex_date {
  width: 220px;
}
.cv_experience_list li .cv_ex_info {
  width: calc(100% - 220px);
}
.cv_experience_list li .cv_ex_info p {
  margin-bottom: 0;
}
.print_cv {
  position: absolute;
  right: 0;
  top: 120px;
  background: #3f6ad8;
  color: #fff;
  padding: 10px 30px;
  border: none;
}
@media print {
  page {
    background: white;
    display: block;
    margin: 0px auto;
    margin-bottom: 0.5cm;
  }
  page[size="A4"] {
    width: 21cm;
    height: 29.7cm;
    padding: 30px;
  }
  page[size="A4"][layout="landscape"] {
    width: 29.7cm;
    height: 21cm;
  }
  .cv_title {
    text-align: center;
    font-weight: bold;
  }
  .cv_user_img {
    width: 130px;
    height: 150px;
    overflow: hidden;
    border: 1px solid #000;
    margin-right: 30px;
    margin-bottom: 30px;
  }
  .cv_user_img img {
    width: 100%;
  }
  .cv_header {
    display: flex;
    align-items: flex-start;
  }
  .cv_header_info {
    width: calc(100% - 150px);
  }
  .cv_header_info h2 {
    font-weight: bold;
  }
  .cv_block p {
    font-size: 16px;
  }
  .cv_header_info p {
    margin-bottom: 0;
  }
  .cv_body_list {
    display: flex;
    flex-wrap: wrap;
  }
  .cv_body_list li {
    list-style: none;
    width: 60%;
    margin-bottom: 10px;
  }
  .cv_body_list li:nth-child(odd) {
    width: 40%;
  }
  .cv_body_list li {
    display: flex;
    flex-direction: column;
  }
  .cv_body_list li:last-child {
    width: 100%;
  }
  .cv_exper_subtitle {
    text-align: center;
    font-weight: bold;
  }
  .cv_experience_list {
  }
  .cv_experience_list li {
    list-style: none;
    display: flex;
    justify-content: space-between;
  }
  .cv_ex_date {
    width: 220px;
  }
  .cv_experience_list li .cv_ex_info {
    width: calc(100% - 220px);
  }
  .cv_experience_list li .cv_ex_info p {
    margin-bottom: 0;
  }
}
</style>
