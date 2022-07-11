<template>

	<div class="not-auth mlr-25-p" v-if="auth === false">
		<img class="logo-img" src="../assets/img/logo.png">

		<h1>Login</h1>
		<div class="container-fluid login-div">
			<div class="row mb-3">
				<div class="col mlr-25-p"><input class="form-control" type="text" v-model="name" placeholder="Enter Name" /></div>
			</div>
			<div class="row mb-3">
				<div class="col mlr-25-p"><input class="form-control" type="password" v-model="password" placeholder="Enter Password" /></div>
			</div>
			<div class="row mb-3">
				<div class="col mlr-25-p"><button class="btn btn-block btn-success btn-lg" v-on:click="login">Login</button></div>
				<div class="alert alert-danger mt-3 width-50-p mlr-25-p" role="alert" v-if="error === true">
					Login Attempt Failed! Please try again.
				</div>
			</div>
		</div>
	</div>

	<div class="auth" v-if="auth === true">
		<h1>Quotes of the Simpsons</h1>

		<div class="container-fluid mt-3">
			<div class="row mb-3 mlr-25-p">
				<div class="col-md-6">
					<button class="btn btn-success btn-lg" v-on:click="newQuote">Next Quote</button>
				</div>
				<div class="col-md-6">
					<button class="btn btn-danger btn-lg" v-on:click="logout">Logout</button>
				</div>
			</div>
		</div>

		<div v-for="quote in quotes" :key="quote">
			<div class="card mb-3 character-card-div">
				<div class="row g-0">
					<div class="col-md-4">
						<img class="character-img" :src="quote.image">
					</div>
					<div class="col-md-8">
						<div class="card-body character-quote-div">
							<h5> {{ quote.character }} </h5>
							<p class="card-text">{{ quote.quote }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</template>

<script>
	import axios from 'axios';
	export default{
		name: "Home",
		data(){
			return{
				name:'',
				password:'',
				auth: false,
				quotes: [],
				error: false
			}
		},
		methods:{
			login(){
				let resault = axios.post(
					'http://localhost:8000/login', {
						name: this.name,
						password: this.password
					})
					.then(response => {
						localStorage.setItem("token", response.data.token);
						this.auth = true;
						this.newQuote();
						console.warn(response.data.token);
					})
					.catch(e => {
						this.error = true;
					});
			},
			logout(){
				localStorage.removeItem("token");
				this.auth = false;
			},
			newQuote(){
				let resault = axios.get(
					'http://localhost:8000/quotes', {
						headers: { Authorization: `Bearer `+localStorage.getItem("token") }
					})
					.then(response => {
						console.log(response.data);
						this.quotes = response.data;
					})
					.catch(e => {
						localStorage.removeItem("token");
						this.auth = false;
					});
			}
		},
		mounted(){
			let token = localStorage.getItem("token");
			if(token){
				this.auth = true;
				this.newQuote();
			}
		}
	}
</script>