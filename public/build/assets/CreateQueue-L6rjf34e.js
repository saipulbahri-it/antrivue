import{_ as a}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{c,o as s,e as i,j as u,t as d}from"./app-I_EO46gW.js";const p={data(){return{ticket:null}},methods:{async create(){const e=await fetch("/api/queues",{method:"POST"});this.ticket=await e.json()},printTicket(){const e=window.open("","","width=300,height=600");e.document.write(`
          <div style="text-align: center; font-size: 20px;">
            <p>=== Nomor Antrian ===</p>
            <h1 style="font-size: 50px;">${this.ticket.queue_number}</h1>
            <p>${new Date().toLocaleString()}</p>
            <p>=====================</p>
          </div>
        `),e.document.close(),e.focus(),e.print(),e.close()}}},l={class:"create-queue"},m={key:0};function k(e,t,_,f,r,n){return s(),c("div",l,[i("button",{onClick:t[0]||(t[0]=(...o)=>n.create&&n.create(...o))},"Ambil Nomor Antrean"),r.ticket?(s(),c("div",m,[i("h3",null,"Nomor Anda: "+d(r.ticket.queue_number),1),i("button",{onClick:t[1]||(t[1]=(...o)=>n.printTicket&&n.printTicket(...o))},"Cetak Tiket")])):u("",!0)])}const x=a(p,[["render",k],["__scopeId","data-v-6055cd6e"]]);export{x as default};
