<template>
  <div>
    <v-menu v-if="requiredate">
      <v-text-field
        slot="activator"
        :value="date"
        label="Date of Consumption"
        prepend-icon="date_range"
        readonly
        max-width="290"
      ></v-text-field>
      <v-date-picker
        v-model="date"
        color="green lighten-1"
        @change="checkdate"
        id="date"
        :max="new Date().toISOString().substr(0, 10)"
      ></v-date-picker>
    </v-menu>
    <div class="form-group">
      <label for="locale">Select Survey Language</label>
      <select v-model="slocale" @change="onlocaleChange" id="locale" class="form-control">
        <option value="en">English</option>
        <option value="bg">Swahili</option>
        <option value="it">Vernacular</option>
      </select>
    </div>

    <survey :survey="survey"></survey>
  </div>
</template>

<script>
import * as SurveyVue from "survey-vue";
import * as swahiliStrings from "../swahili";
var Survey = SurveyVue.Survey;
Survey.cssType = "material";
Survey.sendResultOnPageNext = true;

import format from "date-fns/format";

import * as widgets from "surveyjs-widgets";

Object.filter = (obj, predicate) =>
  Object.keys(obj)
    .filter((key) => predicate(obj[key]))
    .reduce((res, key) => Object.assign(res, { [key]: obj[key] }), {});

const widgetsList = Object.filter(
  SurveyConfig.widgets,
  (widget) => widget === true
);

Object.keys(widgetsList).forEach(function (widget) {
  widgets[widget](SurveyVue);
});

export default {
  components: {
    Survey,
  },
  props: ["surveyData", "selectedphone", "callSession", "jsonData"],

  data() {
    var url = window.location.href.split("/");
    // console.log(url);
    return {
      survey: {},
      respondent: "",
      agent: "",
      date: "",
      jsondata: "",
      project: "",
      phonenumber: "",
      sid: "",
      count: 0,
      sessionId: "",
      slocale: "en",
      requiredate: false,
      showsurvey: false,
      nit: 1,
      phone: url.pop(),
    };
  },
  created() {
    this.survey = new SurveyVue.Model(this.surveyData.json);
    // console.log(this.survey)
    console.log(this.surveyData);

    this.survey.sendResultOnPageNext = true;
    this.nit = this.surveyData.num;
    this.agent = this.surveyData[0];
    console.log(this.agent);
    this.project = this.surveyData.project;
    this.sid = this.surveyData.id;

    // this.respondent = this.surveyData[0];
    // this.jsondata = JSON.stringify(this.jsonData);
    this.phonenumber = this.selectedphone;
    // this.sessionId = this.callSession;
    localStorage.setItem("count", this.count);
    localStorage.setItem("phone", this.phonenumber);

    // console.log(this.jsondata);
  },
  methods: {
    onlocaleChange() {
      this.survey.locale = this.slocale;
      console.log(this.slocale);
      this.survey.render();
    },
  },
  computed: {
    formattedDate() {
      return this.date ? format(this.date, "dddd, MMMM Do YYYY") : "";
    },
  },
  watch: {
    page() {
      //   this.loadResults();
      //   this.survey.sendResultOnPageNext = true;
      this.survey.data = this.jsondata;

      // console.log(this.survey.data);
    },
  },
  mounted() {
    this.survey.sendResultOnPageNext = true;
    // this.loadResults();
    console.log(this.$route);
    if (this.project == 24) {
      this.requiredate = true;
      //   this.showsurvey = true;
    } else {
      this.showsurvey = true;
    }
    this.survey.data = {
      Q8: {
        primary: this.selectedphone,
      },
    };

    // this.survey.data = result;
    this.survey.onComplete.add((result) => {
      console.log(this.phone);
      let url = `/survey/${this.surveyData.id}/result`;
      axios
        .post(url, {
          json: this.survey.data,
          agent: this.agent,
          respondent: this.respondent.respondent,
          survey: this.sid,
          callsession: window.CallSession,
          phonenumber: this.phonenumber,
          phone: this.phone,
          project: this.surveyData.project,
          date: this.date,
        })
        .then((response) => {
          console.log(response);
          this.count++;
          localStorage.setItem("count", this.count);
          localStorage.setItem("reload", 1);
          console.log(this.count);
          if (this.count == this.nit) {
            this.$toastr.s("Interview Successfully Finished");
            // window.location.assign("/agent/project/33");
            // window.history.back();
            window.location = "/agent";
          } else {
            this.survey.clear();
            this.$toastr.i(
              "The  interview has been successfully saved",
              "Success"
            );
            this.survey.render();
          }
        });
    });
  },
};
</script>
<style lang="scss">
.sv_q_description span {
  color: red;
  font-size: large;
}
</style>
