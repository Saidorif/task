<template>
    <div id="preloader">
        <div id="ctn-preloader" class="ctn-preloader loaded">
            <div class="animation-preloader">
                <div class="spinner"></div>
                <div class="txt-loading">
                    <span data-text-preloader="O'" class="letters-loading">O'</span>
                    <span data-text-preloader="Z" class="letters-loading">Z</span>
                    <span data-text-preloader="B" class="letters-loading">B</span>
                    <span data-text-preloader="E" class="letters-loading">E</span>
                    <span data-text-preloader="K" class="letters-loading">K</span>
                    <span data-text-preloader="I" class="letters-loading">I</span>
                    <span data-text-preloader="S" class="letters-loading">S</span>
                    <span data-text-preloader="T" class="letters-loading">T</span>
                    <span data-text-preloader="O" class="letters-loading">O</span>
                    <span data-text-preloader="N" class="letters-loading">N</span>
                    <span data-text-preloader="" class="letters-loading">&nbsp;</span>
                    <span data-text-preloader="R" class="letters-loading">R</span>
                    <span data-text-preloader="E" class="letters-loading">E</span>
                    <span data-text-preloader="S" class="letters-loading">S</span>
                    <span data-text-preloader="P" class="letters-loading">P</span>
                    <span data-text-preloader="U" class="letters-loading">U</span>
                    <span data-text-preloader="B" class="letters-loading">B</span>
                    <span data-text-preloader="L" class="letters-loading">L</span>
                    <span data-text-preloader="I" class="letters-loading">I</span>
                    <span data-text-preloader="K" class="letters-loading">K</span>
                    <span data-text-preloader="A" class="letters-loading">A</span>
                    <span data-text-preloader="S" class="letters-loading">S</span>
                    <span data-text-preloader="I" class="letters-loading">I</span>
                </div>
                <div class="txt-loading">
                    <span data-text-preloader="T" class="letters-loading">T</span>
                    <span data-text-preloader="R" class="letters-loading">R</span>
                    <span data-text-preloader="A" class="letters-loading">A</span>
                    <span data-text-preloader="N" class="letters-loading">N</span>
                    <span data-text-preloader="S" class="letters-loading">S</span>
                    <span data-text-preloader="P" class="letters-loading">P</span>
                    <span data-text-preloader="O" class="letters-loading">O</span>
                    <span data-text-preloader="R" class="letters-loading">R</span>
                    <span data-text-preloader="T" class="letters-loading">T</span>
                    <span data-text-preloader="" class="letters-loading">&nbsp;</span>
                    <span data-text-preloader="V" class="letters-loading">V</span>
                    <span data-text-preloader="A" class="letters-loading">A</span>
                    <span data-text-preloader="Z" class="letters-loading">Z</span>
                    <span data-text-preloader="I" class="letters-loading">I</span>
                    <span data-text-preloader="R" class="letters-loading">R</span>
                    <span data-text-preloader="L" class="letters-loading">L</span>
                    <span data-text-preloader="I" class="letters-loading">I</span>
                    <span data-text-preloader="G" class="letters-loading">G</span>
                    <span data-text-preloader="I" class="letters-loading">I</span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
  data() {return {};},
  computed: {},
  async mounted() {},
  methods: {}
};
</script>
<style scoped>
#preloader {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #fff;
    z-index: 9999;
    opacity: 1;
}
.ctn-preloader {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    cursor: default;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    height: 100%;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    z-index: 9000;
}
.ctn-preloader.loaded .animation-preloader {
    opacity: 1;
    -webkit-transition: 0.3s ease-out;
    -o-transition: 0.3s ease-out;
    transition: 0.3s ease-out;
}
.ctn-preloader .animation-preloader {
    position: absolute;
    z-index: 1000;
}
.ctn-preloader .loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    font-size: 0;
    z-index: 1;
    pointer-events: none;
}
.ctn-preloader .animation-preloader .spinner {
    -webkit-animation: spinner 1s infinite linear;
    animation: spinner 1s infinite linear;
    border-radius: 50%;
    border: 3px solid #1f1c5f3b;
    border-top-color: #3490dc;
    height: 9em;
    margin: 0 auto 3.5em auto;
    width: 9em;
}

.ctn-preloader .animation-preloader .txt-loading {
    text-align: center;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    font-size: 3em;
    font-weight: bold;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading {
    color: #1f1c5f3b;
    position: relative;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:before {
    -webkit-animation: letters-loading 4s infinite;
    animation: letters-loading 4s infinite;
    color: #3490dc;
    content: attr(data-text-preloader);
    left: 0;
    opacity: 0;
    position: absolute;
    top: -8px;
    -webkit-transform: rotateY(-90deg);
    transform: rotateY(-90deg);
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(2):before {
    -webkit-animation-delay: 0.2s;
    animation-delay: 0.2s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(3):before {
    -webkit-animation-delay: 0.4s;
    animation-delay: 0.4s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(4):before {
    -webkit-animation-delay: 0.6s;
    animation-delay: 0.6s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(5):before {
    -webkit-animation-delay: 0.8s;
    animation-delay: 0.8s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(6):before {
    -webkit-animation-delay: 1s;
    animation-delay: 1s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(7):before {
    -webkit-animation-delay: 1.2s;
    animation-delay: 1.2s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(8):before {
    -webkit-animation-delay: 1.4s;
    animation-delay: 1.4s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(9):before {
    -webkit-animation-delay: 1.6s;
    animation-delay: 1.6s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(10):before {
    -webkit-animation-delay: 1.8s;
    animation-delay: 1.8s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(11):before {
    -webkit-animation-delay: 2s;
    animation-delay: 2s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(12):before {
    -webkit-animation-delay: 2.2s;
    animation-delay: 2.2s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(13):before {
    -webkit-animation-delay: 2.4s;
    animation-delay: 2.4s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(14):before {
    -webkit-animation-delay: 2.6s;
    animation-delay: 2.6s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(15):before {
    -webkit-animation-delay: 2.8s;
    animation-delay: 2.8s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(16):before {
    -webkit-animation-delay:3s;
    animation-delay: 3s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(17):before {
    -webkit-animation-delay: 3.2s;
    animation-delay: 3.2s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(18):before {
    -webkit-animation-delay: 3.4s;
    animation-delay: 3.4s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(19):before {
    -webkit-animation-delay: 3.6s;
    animation-delay: 3.6s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(20):before {
    -webkit-animation-delay: 3.8s;
    animation-delay: 3.8s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(21):before {
    -webkit-animation-delay: 4s;
    animation-delay: 4s;
}
.ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(22):before {
    -webkit-animation-delay: 4.2s;
    animation-delay: 4.2s;
}
@keyframes spinner {
    to{
        transform:rotateZ(360deg);
    }
}
@keyframes letters-loading {
    0%,
    75%,
    100%{opacity:0;-webkit-transform:rotateY(-90deg);transform:rotateY(-90deg);}
    25%,
    50%{opacity:1;-webkit-transform:rotateY(0deg);transform:rotateY(0deg);}
}
</style>
