<template>
    <Header></Header>
    <main class="flex-grow">
        <slot></slot>
    </main>
    <Footer></Footer>
</template>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}
</style>