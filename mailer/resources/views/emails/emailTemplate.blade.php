<div class='email-wrapper'>
    <div class='email-container'>
        <div class='email-content'>
            <div class='email-title'>
                <h1>Hello, you have recieved a blessing from Chuck Norris</h1>
                {{$joke}}
                <br/>
                <p>Domain of email: {{$domain}}</p>
                <p>Email Sender: {{$senderName}}</p>
            </div>
        </div>
    </div>
</div>

<style>
    .email-content { 
        display: flex;
        justify-content: center;
    }
</style>