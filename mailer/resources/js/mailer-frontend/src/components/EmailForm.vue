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
                jokes: []
            }
        },
        methods: { 
            prepEmail() { 
                let emailText = document.getElementById('emailInput').value
                let parsedEmails = this.parseEmails(emailText);

                parsedEmails.forEach(parsedEmail => {
                    if(this.validateEmail(parsedEmail)) { 
                            this.getJoke()
                            this.divideEmailValue()
                            this.sendEmail();
                    } else { 

                    }
                });
                //for each parsed email
                //validate
                //if validation true
                //for each validated email
                //get joke, 
                //send email
                //email successful message
            },
            parseEmails(emailText) { 
                //trim, and create array of the emails
                var emailArray = emailText.replaceAll('[\\n ]+','').split(' '); 

                return emailArray
                
            },
            validateEmail(parsedEmail) { 
                if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(parsedEmail))
                {
                    console.log('valid email address!!!')
                    return (true)
                }
                console.log('error, invalid email address')
                    return (false)
            },
            async getJoke() { 
                this.loading = true
                try {
                    const response = await axios.get(
                        'http://api.icndb.com/jokes/random'
                    );
                        
                    if(!isEmpty(response.data)) {
                        if(response.data.status == 'success') { 
                            console.log(response.data)
                            this.jokes.push(response.data.value)
                            this.loading = false

                        }
                    }   

                } catch (error) {
                    console.log(error);
                }
                this.loading = false
            },
            sendEmail() { 
                this.axios.get('inset_my_route_here', {
                    body: JSON.stringify(this.jokes),
                    headers: {
                        'content-type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    console.log("email sent", data);
                })
                .catch(err => console.error(err.toString()));
            },
            successMessage() { 

            },
            errorMessage(){
                
            }
        }
    }
</script>

<style scoped>
    .email-text { 
        width: 50%;
        height: 50px;
    }
    button { 
        width: 70px;
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