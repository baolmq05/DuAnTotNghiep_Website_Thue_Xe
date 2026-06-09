<template>
    <Header></Header>
    <main class="container mx-auto p-4">
        <slot></slot>
    </main>
    <Footer></Footer>
</template>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Roboto;
}
</style>