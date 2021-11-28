//Constantes globales.
const tokenCsrf = document.querySelector('meta[name="csrf-token"]')

const UI = (() => {
    'use strict'

    /**
     * Bloquea botón submit de formulario.
     * @param {*} boton Elemento botón a bloquear.
     * @param {*} bloquea true: Bloquea botón,
     * false: Desbloquea botón.
     */
    const bloqueaBotonForm = (boton, bloquea = true) => {
        if (bloquea) {
            boton.disabled = true;
            boton.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...`
        } else {
            boton.disabled = false
            boton.innerHTML = 'Confirmar'
        }
    }

    /**
     * Muestra mensaje de alerta.
     * @param {*} contenedor Elemento contenedor de la alerta.
     * @param {*} texto Texto ó mensaje de la alerta.
     * @param {*} tipo Tipo de alerta, ej: alert-success, 
     * alert-danger, alert-warning, alert-info.
     */
    const muestraAlerta = (contenedor, texto, tipo) => {
        let html = `
        <div class="alert ${tipo} p-2 text-center" role="alert">
            ${texto}
        </div>`
        contenedor.innerHTML = html
    }

    /**
     * Muestra toast.
     * @param {*} contenedor Elemento contenedor del toast.
     * @param {*} texto Texto ó mensaje del toast.
     * @param {*} tipo Tipo de toast color de fondo, ej: bg-success,
     * bg-danger, bg-warning, bg-info.
     */
    const muestraToast = (contenedor, texto, tipo) => {
        let html = `
        <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 11">
            <div id="liveToast" class="toast align-items-center text-white ${tipo} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${texto}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>`
        contenedor.innerHTML = html
        const liveToast = document.getElementById('liveToast')
        let toast = new bootstrap.Toast(liveToast)
        toast.show()
    }

    return {
        bloqueaBotonForm: bloqueaBotonForm,
        muestraAlerta: muestraAlerta,
        muestraToast: muestraToast
    }
})()

const HttpCtrl = ((UI) => {
    'use strict'
    
    /**
     * Petición ó solicitud al servidor.
     * @param {*} url Enlace al controlador.
     * @param {*} data Datos del formulario.
     * @returns Json
     */
    const post = async (url, data) => {
        const header = new Headers({'csrf-token': tokenCsrf.content})
        const resp = await fetch(url, {
            method: 'POST',
            body: data,
            headers: header
        })
        return await resp.json()
    }

    /**
     * Petición ó solicitud para uso general.
     * @param {*} event Evento del submit.
     * @param {*} ctrl Nombre ó enlace del controlador.
     * @param {*} boton Elemento del botón submit.
     * @param {*} contenedorAlerta Elemento del contenedor de la alerta.
     * @param {*} contenedorToast Elemento del contenedor del toast.
     */
    const postGeneral = (event, ctrl, boton, contenedorAlerta, contenedorToast) => {
        const form = new FormData(event.currentTarget)
        UI.bloqueaBotonForm(boton)
        post(ctrl, form).then(respuesta => {
            UI.bloqueaBotonForm(boton, false)
            //Alerta a mostrar.
            if (respuesta.tipoAlerta && respuesta.textoAlerta) {
                UI.muestraAlerta(contenedorAlerta, respuesta.textoAlerta, respuesta.tipoAlerta)
            }

            //Toast a mostrar.
            if (respuesta.tipoToast && respuesta.textoToast) {
                UI.muestraToast(contenedorToast, respuesta.textoToast, respuesta.tipoToast)
            }

            //Token ha actualizar.
            if (respuesta.nuevoToken) {
                tokenCsrf.content = respuesta.nuevoToken
            }

            //Limpia campos del formulario.
            if (respuesta.limpiaForm) {
                event.target.reset()
            }

            //Recarga o redirecciona página
            if (respuesta.url) {
                if (typeof respuesta.url === 'string') {
                    window.location.replace(respuesta.url)
                } else {
                    window.location.reload(true)
                }
            }
        }).catch((e) => {
            UI.bloqueaBotonForm(boton, false)
            let msgT = `Se detectó una inconsistencia en el sistema, 
            por favor vuelva a intentar. Si la inconsistencia persiste 
            favor de informar a soporte. Mensaje: ${e.message}`
            let tipoAlertaT = 'alert-danger'
            UI.muestraAlerta(contenedorAlerta, msgT, tipoAlertaT)
        })
    }

    return {
        postGeneral: postGeneral
    }
})(UI)

const Usuario = ((HttpCtrl) => {
    'use strict'

    const eventosNuevoUsuario = () => {
        const formNuevoUsuario = document.getElementById('formNuevoUsuario'),
        botonNuevoUsuario = document.getElementById('botonNuevoUsuario'),
        contAlertaNuevoUsuario = document.getElementById('contAlertaNuevoUsuario')
        
        formNuevoUsuario.addEventListener('submit', (event) => {
            event.preventDefault()
            HttpCtrl.postGeneral(event, 'registro', botonNuevoUsuario, contAlertaNuevoUsuario)
        })
    }

    const eventosLogin = () => {
        const formLogin = document.getElementById('formLogin'),
        botonLogin = document.getElementById('botonLogin'),
        contAlertaLogin = document.getElementById('contAlertaLogin')
        
        formLogin.addEventListener('submit', (event) => {
            event.preventDefault()
            HttpCtrl.postGeneral(event, 'login', botonLogin, contAlertaLogin)
        })
    }

    return {
        eventosNuevoUsuario: eventosNuevoUsuario,
        eventosLogin: eventosLogin
    }
})(HttpCtrl)