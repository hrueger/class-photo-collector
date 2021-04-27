import * as fs from "fs";
import { sync as rimrafSync } from "rimraf";
import * as cliProgress from "cli-progress";

console.log("Cleaing up")
rimrafSync("einverstaendniserklaerungen");
rimrafSync("fotos");


console.log("Exporting & Renaming")
const bar = new cliProgress.SingleBar({});
let classes = fs.readdirSync("raw");
classes = classes.filter((n) => n !== "node_modules" && fs.lstatSync(`raw/${n}`).isDirectory());
bar.start(classes.length, 0, {})

for (const cls of classes) {
    fs.mkdirSync(`fotos/${cls}`, { recursive: true });
    fs.mkdirSync(`einverstaendniserklaerungen/${cls}`, { recursive: true });
    const files = fs.readdirSync(`raw/${cls}`);
    const fotos = files.filter((n) => n.includes("Foto"));
    const einverstaendniserklaerungen = files.filter((n) => n.includes("Einverstaendniserklaerung"));
    const lehrer = fs.readdirSync(`raw/${cls}/Lehrkraefte`);

    if (fotos.length !== einverstaendniserklaerungen.length) {
        console.log("Error at", cls);
        process.exit(1);
    }
    for (const foto of fotos) {
        fs.copyFileSync(`raw/${cls}/${foto}`, `fotos/${cls}/${foto.replace("Foto", cls)}`);
    }
    for (const einverstaendniserklaerung of einverstaendniserklaerungen) {
        fs.copyFileSync(`raw/${cls}/${einverstaendniserklaerung}`, `einverstaendniserklaerungen/${cls}/${einverstaendniserklaerung.replace("Einverstaendniserklaerung", cls)}`);
    }

    for (const lehrkraft of lehrer) {
        fs.copyFileSync(`raw/${cls}/Lehrkraefte/${lehrkraft}`, `fotos/${cls}/_Lehrkraft ${cls} ${lehrkraft}`);
    }
    bar.increment();
}
bar.stop();
console.log("Done");