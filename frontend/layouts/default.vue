<template>
    <Header></Header>
    <main class="flex-grow overflow-x-hidden pb-[80px] md:pb-0">
        <slot></slot>
    </main>
    <Footer></Footer>
    <AuthLogin />
    <AuthRegister />
    <CommonToast />
    <CommonRegisterSuccessModal />
</template>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}
</style>