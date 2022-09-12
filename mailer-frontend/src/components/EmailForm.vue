<template>
    <div class="form-wrapper">
        <textarea class="email-text" id="emailInput" rows="1" value="">

        </textarea>
        <button @click="prepEmail">Send emails</button>
        <br/>
        <div id="confirmation">

        </div>

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
            /*This method start off async funtionality by getting each value in the text area
            and then proceeding to parse the email for validation, then we will get an API 
            for each email and if the email is valid + api returns 200 , we will send an email*/
            async prepEmail() { 
                let emailText = document.getElementById('emailInput').value
                let parsedEmails = this.parseEmails(emailText);
                let confirmationArea = document.getElementById('confirmation')
                confirmationArea.innerHTML = "";
                let confirmationText = document.createElement("p")
                await parsedEmails.forEach(async parsedEmail => {
                    if(this.validateEmail(parsedEmail)) { 
                        await this.getJoke();
                        let email = parsedEmail;
                        await this.divideEmailValue(email)
                        await this.sendEmail(email, this.jokes, this.name, this.domain)
                    } else { 
                        confirmationText.style.color = "red"; 
                        confirmationText.innerHTML = 'error with email ' + parsedEmail
                    }
                });

            },
            //Receives a string of 'email' and seperates the name from the domain part of email
            divideEmailValue(email) {
                let name   = email.substring(0, email.lastIndexOf("@"));
                let domain = email.substring(email.lastIndexOf("@") +1);

                let confirmationArea = document.getElementById('confirmation')
                let confirmationText = document.createElement("p")
                this.name = '' 

                confirmationText.style.color = "green"; 

                confirmationText.textContent = 'name is ' +name+ ' domain is ' +domain;
                confirmationArea.append(confirmationText)

                this.name = name;
                this.domain = domain;

                console.log( name );   // john.doe
                console.log( domain ); // example.com

            },
            //trim, and create array of the emails
            parseEmails(emailText) { 
                var emailArray = emailText.replaceAll('[\\n ]+','').split(' '); 

                return emailArray
                
            },
            //receiving a string and running string through email regex validation
            validateEmail(parsedEmail) { 
                let confirmationArea = document.getElementById('confirmation')
                let confirmationText = document.createElement("p")

                if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(parsedEmail))
                {
                    console.log('valid email')
                    this.email = parsedEmail;
                    return (true, parsedEmail)
                } else { 
                    confirmationText.style.color = "red"; 
                    confirmationText.innerHTML = 'Error with email' + parsedEmail
                    confirmationArea.append(confirmationText)
                    console.log('error with email')
                }
                    return (false)
            },
            /*/method to obtain joke from the random chuck norris api, and attemps to replace odd characters 
            with spaces for cleaner code sets state variable as the joke string to be accessed later*/
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
            /*triggering backend API end point which is send all infor from front end to backend
            there is a Laravel email command to send email with info provided*/
            sendEmail(email) { 
                let confirmationArea = document.getElementById('confirmation')
                let confirmationText = document.createElement("p")

                axios.post('http://127.0.0.1:8000/api/send-email', {

                    body: {'joke': JSON.stringify(this.jokes).replace(/[^\w\s]/gi, ''), 'sendTo': email, "domain": this.domain, "senderName": this.name},
                    headers: {
                        'content-type': 'application/json',
                    }
                })
                .then(data => {
                    console.log("email sent", data);
                    if(data.status == 200) { 
                        confirmationText.style.color = "green"; 
                        confirmationText.textContent = 'Email Successfully Sent to ' + email 
                        confirmationArea.append(confirmationText)
                    } else { 
                        confirmationText.style.color = "red"; 
                        confirmationText.textContent = 'There appears to be an error with the email sending';
                        confirmationArea.append(confirmationText)
                    }
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
    .success { 
        color: green
    }
    .error { 
        color: red;
    }

</style>