
export  const isImage = (attachment) =>{

    let mime = attachment.mime || attachment.type
     mime = mime.split('/')
    return mime[0].toLowerCase() ==  'image'
}
// function isImage(attachment) {
//     const  mime = attachment.mime.split('/')
//     return mime[0].toLowerCase() ==  'image'
// }
