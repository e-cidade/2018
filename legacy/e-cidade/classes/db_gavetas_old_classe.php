<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

//MODULO: Cemiterio
//CLASSE DA ENTIDADE gavetas_old
class cl_gavetas_old {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $cm13_i_codigo = 0;
   var $cm13_i_jazigo = 0;
   var $cm13_i_sepultamento = 0;
   var $cm13_i_gaveta = 0;
   var $cm13_i_medico = 0;
   var $cm13_d_exumprevista_dia = null;
   var $cm13_d_exumprevista_mes = null;
   var $cm13_d_exumprevista_ano = null;
   var $cm13_d_exumprevista = null;
   var $cm13_d_exumfeita_dia = null;
   var $cm13_d_exumfeita_mes = null;
   var $cm13_d_exumfeita_ano = null;
   var $cm13_d_exumfeita = null;
   var $cm13_c_ossario = null;
   var $cm13_c_campa = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 cm13_i_codigo = int4 = Código
                 cm13_i_jazigo = int4 = Jazigo
                 cm13_i_sepultamento = int4 = Código Sepultamento
                 cm13_i_gaveta = int4 = Numero da Gaveta
                 cm13_i_medico = int4 = Médico
                 cm13_d_exumprevista = date = Previsão de Exumação
                 cm13_d_exumfeita = date = Exumação pelo Médico
                 cm13_c_ossario = char(1) = Ossário
                 cm13_c_campa = char(1) = Campa
                 ";
   //funcao construtor da classe
   function cl_gavetas_old() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("gavetas_old");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->cm13_i_codigo = ($this->cm13_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm13_i_codigo"]:$this->cm13_i_codigo);
       $this->cm13_i_jazigo = ($this->cm13_i_jazigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm13_i_jazigo"]:$this->cm13_i_jazigo);
       $this->cm13_i_sepultamento = ($this->cm13_i_sepultamento == ""?@$GLOBALS["HTTP_POST_VARS"]["cm13_i_sepultamento"]:$this->cm13_i_sepultamento);
       $this->cm13_i_gaveta = ($this->cm13_i_gaveta == ""?@$GLOBALS["HTTP_POST_VARS"]["cm13_i_gaveta"]:$this->cm13_i_gaveta);
       $this->cm13_i_medico = ($this->cm13_i_medico == ""?@$GLOBALS["HTTP_POST_VARS"]["cm13_i_medico"]:$this->cm13_i_medico);
       if($this->cm13_d_exumprevista == ""){
         $this->cm13_d_exumprevista_dia = ($this->cm13_d_exumprevista_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm13_d_exumprevista_dia"]:$this->cm13_d_exumprevista_dia);
         $this->cm13_d_exumprevista_mes = ($this->cm13_d_exumprevista_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm13_d_exumprevista_mes"]:$this->cm13_d_exumprevista_mes);
         $this->cm13_d_exumprevista_ano = ($this->cm13_d_exumprevista_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm13_d_exumprevista_ano"]:$this->cm13_d_exumprevista_ano);
         if($this->cm13_d_exumprevista_dia != ""){
            $this->cm13_d_exumprevista = $this->cm13_d_exumprevista_ano."-".$this->cm13_d_exumprevista_mes."-".$this->cm13_d_exumprevista_dia;
         }
       }
       if($this->cm13_d_exumfeita == ""){
         $this->cm13_d_exumfeita_dia = ($this->cm13_d_exumfeita_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm13_d_exumfeita_dia"]:$this->cm13_d_exumfeita_dia);
         $this->cm13_d_exumfeita_mes = ($this->cm13_d_exumfeita_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm13_d_exumfeita_mes"]:$this->cm13_d_exumfeita_mes);
         $this->cm13_d_exumfeita_ano = ($this->cm13_d_exumfeita_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm13_d_exumfeita_ano"]:$this->cm13_d_exumfeita_ano);
         if($this->cm13_d_exumfeita_dia != ""){
            $this->cm13_d_exumfeita = $this->cm13_d_exumfeita_ano."-".$this->cm13_d_exumfeita_mes."-".$this->cm13_d_exumfeita_dia;
         }
       }
       $this->cm13_c_ossario = ($this->cm13_c_ossario == ""?@$GLOBALS["HTTP_POST_VARS"]["cm13_c_ossario"]:$this->cm13_c_ossario);
       $this->cm13_c_campa = ($this->cm13_c_campa == ""?@$GLOBALS["HTTP_POST_VARS"]["cm13_c_campa"]:$this->cm13_c_campa);
     }else{
       $this->cm13_i_codigo = ($this->cm13_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm13_i_codigo"]:$this->cm13_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm13_i_codigo){
      $this->atualizacampos();
     if($this->cm13_i_jazigo == null ){
       $this->erro_sql = " Campo Jazigo nao Informado.";
       $this->erro_campo = "cm13_i_jazigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm13_i_sepultamento == null ){
       $this->erro_sql = " Campo Código Sepultamento nao Informado.";
       $this->erro_campo = "cm13_i_sepultamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm13_i_gaveta == null ){
       $this->erro_sql = " Campo Numero da Gaveta nao Informado.";
       $this->erro_campo = "cm13_i_gaveta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm13_i_medico == null ){
       $this->erro_sql = " Campo Médico nao Informado.";
       $this->erro_campo = "cm13_i_medico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm13_d_exumprevista == null ){
       $this->erro_sql = " Campo Previsão de Exumação nao Informado.";
       $this->erro_campo = "cm13_d_exumprevista_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm13_d_exumfeita == null ){
       $this->erro_sql = " Campo Exumação pelo Médico nao Informado.";
       $this->erro_campo = "cm13_d_exumfeita_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm13_c_ossario == null ){
       $this->erro_sql = " Campo Ossário nao Informado.";
       $this->erro_campo = "cm13_c_ossario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm13_c_campa == null ){
       $this->erro_sql = " Campo Campa nao Informado.";
       $this->erro_campo = "cm13_c_campa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cm13_i_codigo == "" || $cm13_i_codigo == null ){
       $result = db_query("select nextval('gavetas_old_cm13_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: gavetas_old_cm13_i_codigo_seq do campo: cm13_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->cm13_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from gavetas_old_cm13_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm13_i_codigo)){
         $this->erro_sql = " Campo cm13_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm13_i_codigo = $cm13_i_codigo;
       }
     }
     if(($this->cm13_i_codigo == null) || ($this->cm13_i_codigo == "") ){
       $this->erro_sql = " Campo cm13_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into gavetas_old(
                                       cm13_i_codigo
                                      ,cm13_i_jazigo
                                      ,cm13_i_sepultamento
                                      ,cm13_i_gaveta
                                      ,cm13_i_medico
                                      ,cm13_d_exumprevista
                                      ,cm13_d_exumfeita
                                      ,cm13_c_ossario
                                      ,cm13_c_campa
                       )
                values (
                                $this->cm13_i_codigo
                               ,$this->cm13_i_jazigo
                               ,$this->cm13_i_sepultamento
                               ,$this->cm13_i_gaveta
                               ,$this->cm13_i_medico
                               ,".($this->cm13_d_exumprevista == "null" || $this->cm13_d_exumprevista == ""?"null":"'".$this->cm13_d_exumprevista."'")."
                               ,".($this->cm13_d_exumfeita == "null" || $this->cm13_d_exumfeita == ""?"null":"'".$this->cm13_d_exumfeita."'")."
                               ,'$this->cm13_c_ossario'
                               ,'$this->cm13_c_campa'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Gavetas Old ($this->cm13_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Gavetas Old já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Gavetas Old ($this->cm13_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm13_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm13_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10309,'$this->cm13_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1786,10309,'','".AddSlashes(pg_result($resaco,0,'cm13_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1786,10310,'','".AddSlashes(pg_result($resaco,0,'cm13_i_jazigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1786,10311,'','".AddSlashes(pg_result($resaco,0,'cm13_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1786,10312,'','".AddSlashes(pg_result($resaco,0,'cm13_i_gaveta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1786,10313,'','".AddSlashes(pg_result($resaco,0,'cm13_i_medico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1786,10314,'','".AddSlashes(pg_result($resaco,0,'cm13_d_exumprevista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1786,10315,'','".AddSlashes(pg_result($resaco,0,'cm13_d_exumfeita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1786,10316,'','".AddSlashes(pg_result($resaco,0,'cm13_c_ossario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1786,10317,'','".AddSlashes(pg_result($resaco,0,'cm13_c_campa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($cm13_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update gavetas_old set ";
     $virgula = "";
     if(trim($this->cm13_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm13_i_codigo"])){
       $sql  .= $virgula." cm13_i_codigo = $this->cm13_i_codigo ";
       $virgula = ",";
       if(trim($this->cm13_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "cm13_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm13_i_jazigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm13_i_jazigo"])){
       $sql  .= $virgula." cm13_i_jazigo = $this->cm13_i_jazigo ";
       $virgula = ",";
       if(trim($this->cm13_i_jazigo) == null ){
         $this->erro_sql = " Campo Jazigo nao Informado.";
         $this->erro_campo = "cm13_i_jazigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm13_i_sepultamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm13_i_sepultamento"])){
       $sql  .= $virgula." cm13_i_sepultamento = $this->cm13_i_sepultamento ";
       $virgula = ",";
       if(trim($this->cm13_i_sepultamento) == null ){
         $this->erro_sql = " Campo Código Sepultamento nao Informado.";
         $this->erro_campo = "cm13_i_sepultamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm13_i_gaveta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm13_i_gaveta"])){
       $sql  .= $virgula." cm13_i_gaveta = $this->cm13_i_gaveta ";
       $virgula = ",";
       if(trim($this->cm13_i_gaveta) == null ){
         $this->erro_sql = " Campo Numero da Gaveta nao Informado.";
         $this->erro_campo = "cm13_i_gaveta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm13_i_medico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm13_i_medico"])){
       $sql  .= $virgula." cm13_i_medico = $this->cm13_i_medico ";
       $virgula = ",";
       if(trim($this->cm13_i_medico) == null ){
         $this->erro_sql = " Campo Médico nao Informado.";
         $this->erro_campo = "cm13_i_medico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm13_d_exumprevista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm13_d_exumprevista_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm13_d_exumprevista_dia"] !="") ){
       $sql  .= $virgula." cm13_d_exumprevista = '$this->cm13_d_exumprevista' ";
       $virgula = ",";
       if(trim($this->cm13_d_exumprevista) == null ){
         $this->erro_sql = " Campo Previsão de Exumação nao Informado.";
         $this->erro_campo = "cm13_d_exumprevista_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm13_d_exumprevista_dia"])){
         $sql  .= $virgula." cm13_d_exumprevista = null ";
         $virgula = ",";
         if(trim($this->cm13_d_exumprevista) == null ){
           $this->erro_sql = " Campo Previsão de Exumação nao Informado.";
           $this->erro_campo = "cm13_d_exumprevista_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm13_d_exumfeita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm13_d_exumfeita_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm13_d_exumfeita_dia"] !="") ){
       $sql  .= $virgula." cm13_d_exumfeita = '$this->cm13_d_exumfeita' ";
       $virgula = ",";
       if(trim($this->cm13_d_exumfeita) == null ){
         $this->erro_sql = " Campo Exumação pelo Médico nao Informado.";
         $this->erro_campo = "cm13_d_exumfeita_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm13_d_exumfeita_dia"])){
         $sql  .= $virgula." cm13_d_exumfeita = null ";
         $virgula = ",";
         if(trim($this->cm13_d_exumfeita) == null ){
           $this->erro_sql = " Campo Exumação pelo Médico nao Informado.";
           $this->erro_campo = "cm13_d_exumfeita_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm13_c_ossario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm13_c_ossario"])){
       $sql  .= $virgula." cm13_c_ossario = '$this->cm13_c_ossario' ";
       $virgula = ",";
       if(trim($this->cm13_c_ossario) == null ){
         $this->erro_sql = " Campo Ossário nao Informado.";
         $this->erro_campo = "cm13_c_ossario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm13_c_campa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm13_c_campa"])){
       $sql  .= $virgula." cm13_c_campa = '$this->cm13_c_campa' ";
       $virgula = ",";
       if(trim($this->cm13_c_campa) == null ){
         $this->erro_sql = " Campo Campa nao Informado.";
         $this->erro_campo = "cm13_c_campa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cm13_i_codigo!=null){
       $sql .= " cm13_i_codigo = $this->cm13_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm13_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10309,'$this->cm13_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm13_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1786,10309,'".AddSlashes(pg_result($resaco,$conresaco,'cm13_i_codigo'))."','$this->cm13_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm13_i_jazigo"]))
           $resac = db_query("insert into db_acount values($acount,1786,10310,'".AddSlashes(pg_result($resaco,$conresaco,'cm13_i_jazigo'))."','$this->cm13_i_jazigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm13_i_sepultamento"]))
           $resac = db_query("insert into db_acount values($acount,1786,10311,'".AddSlashes(pg_result($resaco,$conresaco,'cm13_i_sepultamento'))."','$this->cm13_i_sepultamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm13_i_gaveta"]))
           $resac = db_query("insert into db_acount values($acount,1786,10312,'".AddSlashes(pg_result($resaco,$conresaco,'cm13_i_gaveta'))."','$this->cm13_i_gaveta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm13_i_medico"]))
           $resac = db_query("insert into db_acount values($acount,1786,10313,'".AddSlashes(pg_result($resaco,$conresaco,'cm13_i_medico'))."','$this->cm13_i_medico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm13_d_exumprevista"]))
           $resac = db_query("insert into db_acount values($acount,1786,10314,'".AddSlashes(pg_result($resaco,$conresaco,'cm13_d_exumprevista'))."','$this->cm13_d_exumprevista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm13_d_exumfeita"]))
           $resac = db_query("insert into db_acount values($acount,1786,10315,'".AddSlashes(pg_result($resaco,$conresaco,'cm13_d_exumfeita'))."','$this->cm13_d_exumfeita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm13_c_ossario"]))
           $resac = db_query("insert into db_acount values($acount,1786,10316,'".AddSlashes(pg_result($resaco,$conresaco,'cm13_c_ossario'))."','$this->cm13_c_ossario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm13_c_campa"]))
           $resac = db_query("insert into db_acount values($acount,1786,10317,'".AddSlashes(pg_result($resaco,$conresaco,'cm13_c_campa'))."','$this->cm13_c_campa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Gavetas Old nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm13_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Gavetas Old nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm13_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm13_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($cm13_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm13_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10309,'$cm13_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1786,10309,'','".AddSlashes(pg_result($resaco,$iresaco,'cm13_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1786,10310,'','".AddSlashes(pg_result($resaco,$iresaco,'cm13_i_jazigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1786,10311,'','".AddSlashes(pg_result($resaco,$iresaco,'cm13_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1786,10312,'','".AddSlashes(pg_result($resaco,$iresaco,'cm13_i_gaveta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1786,10313,'','".AddSlashes(pg_result($resaco,$iresaco,'cm13_i_medico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1786,10314,'','".AddSlashes(pg_result($resaco,$iresaco,'cm13_d_exumprevista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1786,10315,'','".AddSlashes(pg_result($resaco,$iresaco,'cm13_d_exumfeita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1786,10316,'','".AddSlashes(pg_result($resaco,$iresaco,'cm13_c_ossario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1786,10317,'','".AddSlashes(pg_result($resaco,$iresaco,'cm13_c_campa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from gavetas_old
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm13_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm13_i_codigo = $cm13_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Gavetas Old nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm13_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Gavetas Old nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm13_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm13_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:gavetas_old";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cm13_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from gavetas_old ";
     $sql .= "      inner join jazigos  on  jazigos.cm03_i_codigo = gavetas_old.cm13_i_jazigo";
     $sql .= "      inner join sepultamentos  on  sepultamentos.cm01_i_codigo = gavetas_old.cm13_i_sepultamento";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = jazigos.cm03_i_proprietario";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = sepultamentos.cm01_i_codigo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sepultamentos.cm01_i_funcionario";
     $sql .= "      inner join causa  on  causa.cm04_i_codigo = sepultamentos.cm01_i_causa";
     $sql .= "      inner join cemiterio  on  cemiterio.cm14_i_codigo = sepultamentos.cm01_i_cemiterio";
     $sql .= "      left  join funerarias  on  funerarias.cm17_i_funeraria = sepultamentos.cm01_i_funeraria";
     $sql .= "      left  join hospitais  on  hospitais.cm18_i_hospital = sepultamentos.cm01_i_hospital";
     $sql2 = "";
     if($dbwhere==""){
       if($cm13_i_codigo!=null ){
         $sql2 .= " where gavetas_old.cm13_i_codigo = $cm13_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $cm13_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from gavetas_old ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm13_i_codigo!=null ){
         $sql2 .= " where gavetas_old.cm13_i_codigo = $cm13_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>
