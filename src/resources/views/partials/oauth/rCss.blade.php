<style>

*{
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica, sans-serif;
}

    .pageLoader {
    position: relative;
    width: 100%;
    height: 100%;
    -webkit-font-smoothing: antialiased;
    text-rendering: optimizeLegibility;
    font-family: "proxima-nova-soft", sans-serif;
    user-select: none;
    -webkit-user-select: none;
    overflow: hidden;

    .vertical-centered-box {
        position: absolute;
        width: 100%;
        height: 100%;
        text-align: center;

        &:after {
            content: '';
            display: inline-block;
            height: 100%;
            vertical-align: middle;
            margin-right: -0.25em;
        }

        .content {
            box-sizing: border-box;
            display: inline-block;
            vertical-align: middle;
            text-align: left;
            font-size: 0;
        }
    }
}

.loader-circle {
    position: absolute;
    left: 50%;
    top: 50%;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .1);
    margin-left: -60px;
    margin-top: -60px;
}

.loader-line-mask {
    position: absolute;
    left: 50%;
    top: 50%;
    width: 60px;
    height: 120px;
    margin-left: -60px;
    margin-top: -60px;
    overflow: hidden;
    transform-origin: 60px 60px;
    mask-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));
    -webkit-mask-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));
    animation: rotate 1.2s infinite linear;
}

.loader-line {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .5);
}

#particles-background,
#particles-foreground {
    left: -51%;
    top: -51%;
    width: 202%;
    height: 202%;
    transform: scale3d(.5, .5, 1);
}

#particles-background {
    background: mix(#000000, #232325, 70%);
    background-image: -moz-linear-gradient(45deg, #000000 2%, #232325 100%);
    background-image: -webkit-linear-gradient(45deg, #000000 2%, #232325 100%);
    background-image: linear-gradient(45deg, #000000 2%, #232325 100%);
}

@keyframes rotate {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

@keyframes fade {
    0% {
        opacity: 1;
    }

    50% {
        opacity: 0.25;
    }
}

@keyframes fade-in {
    0% {
        opacity: 0;
    }

    100% {
        opacity: 1;
    }
}

.startLoaderImg{
    width: 60px;
    height: 60px;
}

.loaderPowerdByXwms {
    font-weight: 600;
    letter-spacing: 2px;
    font-size: 14px;
    text-transform: uppercase;
    
    background: linear-gradient(270deg, #41d9ff, #2b2fff, #b341ff, #ff2bae);
    background-size: 400% 400%;
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: gradientAnimation 3s infinite linear;
}

@keyframes gradientAnimation {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

#pageLoaderProgression{
    height: 3px;
    transition: width .5s;
    width: 0%;
}

#XWMS_PAGE_LOADER {
    opacity: 1;
    transition: opacity 1s ease-in-out;
    pointer-events: auto;
    position: fixed;
    width: 100%;
    height: 100%;
    background-color: black;
}

#XWMS_PAGE_LOADER.fade-out {
    opacity: 0;
    pointer-events: none;
}

#XWMS_PAGE_LOADER.fade-in {
    opacity: 1;
    pointer-events: auto;
}

.xwms-page-1{
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-around;
    overflow: hidden;
}

form{
    display: none;
}

.xwms-page-2{
    position: absolute;
    bottom: 0;
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: center;
}

.xwms-page-3{
    margin: 0;
    text-align: center;
    text-transform: uppercase;
    margin-bottom: 2rem;
}

.xwms-page-4{
    position: absolute;
    top: 0;
    width: 100%;
    background: -webkit-gradient(linear, left top, right top, from(#da8cff), to(#9a55ff));
    background: linear-gradient(to right, #da8cff, #9a55ff);
}

</style>