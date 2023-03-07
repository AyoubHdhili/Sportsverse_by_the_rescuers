window.onload=()=>{
    const FiltersForm=document.querySelector("#Filters")
    document.querySelectorAll("#Filters input").forEach(input=> {
        input.addEventListener("change",()=>{
            const Form=new FormData(FiltersForm)
                  //fabriquer l querystring
                const Params=new URLSearchParams();


            Form.forEach((value,key)=>{
                Params.append(key,value );
               
            });
            //recuperer l'url
            const url =new URL(window.location.href);
            
            //requete ajax
            fetch(url.pathname + "?" + Params.toString()+"&ajax=1",{
                headers:{
                    "X-Requested-with":"XMLHttpRequest"
                }
            }).then(Response=> {
                console.log(Response)
            }).then(data => {
                // On va chercher la zone de contenu
                const content= document.querySelector("#produit");

                // On remplace le contenu
                content.innerHTML = data.produit;

                // On met à jour l'url
                history.pushState({}, null, Url.pathname + "?" + Params.toString());
            }).catch(e=>alert(e))

        })
    })
}