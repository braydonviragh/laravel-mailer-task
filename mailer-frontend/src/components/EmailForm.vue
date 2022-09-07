<template>
    <div class="form-wrapper">
        <textarea class="email-text" id="emailInput" rows="1" value="">

        </textarea>
        <button @click="prepEmail">Send emails</button>

    </div>
</template>

<script>
    import axios from 'axios';
    import { isEmpty } from 'lodash';

    export default {
        name: 'EmailForm',
        data() { 
            return { 
                loading: false,
                jokes: '',
                email: '',
                domain: '',
                emailerName: ''
            }
        },
        methods: { 
            async prepEmail() { 
                let emailText = document.getElementById('emailInput').value
                let parsedEmails = this.parseEmails(emailText);

                parsedEmails.forEach(async parsedEmail => {
                    if(this.validateEmail(parsedEmail)) { 
                        await this.getJoke();
                        let email = parsedEmail;
                        this.divideEmailValue(email)
                        await this.sendEmail(email, this.jokes, this.name, this.domain)
                    } else { 
                        console.log('error with email')
                    }
                });
            },
            divideEmailValue(email) {
                this.name = '' 
               // var email = //"john.doe@example.com";
                var name   = email.substring(0, email.lastIndexOf("@"));
                var domain = email.substring(email.lastIndexOf("@") +1);

                this.name = name;
                this.domain = domain;

                console.log( name );   // john.doe
                console.log( domain ); // example.com

            },
            parseEmails(emailText) { 
                //trim, and create array of the emails
                var emailArray = emailText.replaceAll('[\\n ]+','').split(' '); 

                return emailArray
                
            },
            validateEmail(parsedEmail) { 
                if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(parsedEmail))
                {
                    console.log('valid email address!')
                    this.email = parsedEmail;
                    return (true, parsedEmail)
                }
                console.log('error, invalid email address')
                    return (false)
            },
            async getJoke() { 
                this.loading = true
                this.jokes = [];
                try {
                    const response = await axios.get(
                        'http://api.icndb.com/jokes/random'
                    );
                    if(!isEmpty(response.data)) {
                        if(response.data.type == 'success') { 
                            var jokeString = JSON.stringify(response.data.value.joke);
                            jokeString.replaceAll('[\\n ]+','')
                            this.jokes = jokeString;
                        }
                    }   

                } catch (error) {
                    console.log(error);
                }
                this.loading = false
            },
            sendEmail(email) { 
                axios.post('http://127.0.0.1:8000/api/send-email', {
                    body: {'joke': JSON.stringify(this.jokes).replace(/&quot;/g,'"'), 'sendTo': email, "domain": this.domain, "senderName": this.name},
                    headers: {
                        'content-type': 'application/json',
                    }
                })
                .then(data => {
                    console.log("email sent", data);
                })
                .catch(err => console.error(err.toString()));
            },
        }
    }
</script>

<style scoped>
    .email-text { 
        width: 50%;
        height: 50px;
    }
    button { 
        width: 100px;
        height: 50px;
        background-color: green;
        color: #fff;
    }
    .form-wrapper { 
        display: flex;
        flex-direction: column;
        align-items: center;
    }

</style>