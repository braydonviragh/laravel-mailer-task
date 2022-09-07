<template>
    <div class="page-wrapper">
        <div class="page-container">
            <div class="page-title">
                <h1>Welcome to the Chuck Norris Challenge</h1>
                <p>Enter emails in the box provided and seperate every email with a SPACE these addresses will receive a dynamically rendered Chuck norris joke from an API</p>
            </div>
            <EmailForm/>
        </div>

    </div>
</template>

<script>
    import EmailForm from './EmailForm.vue';
    export default {
    name: "LandingPage",
    components: { EmailForm }
}
</script>

<style scoped>

</style>