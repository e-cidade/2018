<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: projetos
//CLASSE DA ENTIDADE obrasalvara
class cl_obrasalvara { 
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
   var $ob04_codobra = 0; 
   var $ob04_alvara = 0; 
   var $ob04_data_dia = null; 
   var $ob04_data_mes = null; 
   var $ob04_data_ano = null; 
   var $ob04_data = null; 
   var $ob04_processo = null; 
   var $ob04_titularprocesso = null; 
   var $ob04_dtprocesso_dia = null; 
   var $ob04_dtprocesso_mes = null; 
   var $ob04_dtprocesso_ano = null; 
   var $ob04_dtprocesso = null; 
   var $ob04_obsprocesso = null; 
   var $ob04_dtvalidade_dia = null; 
   var $ob04_dtvalidade_mes = null; 
   var $ob04_dtvalidade_ano = null; 
   var $ob04_dtvalidade = null; 
   var $ob04_dataexpedicao_dia = null; 
   var $ob04_dataexpedicao_mes = null; 
   var $ob04_dataexpedicao_ano = null; 
   var $ob04_dataexpedicao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ob04_codobra = int4 = Código da Obra 
                 ob04_alvara = int4 = Alvará 
                 ob04_data = date = Data do Alvará 
                 ob04_processo = varchar(100) = Código do Processo 
                 ob04_titularprocesso = varchar(100) = Nome do Titular 
                 ob04_dtprocesso = date = Data Processo 
                 ob04_obsprocesso = text = Observações 
                 ob04_dtvalidade = date = Data Validade Alvará 
                 ob04_dataexpedicao = date = Data Expedição 
                 ";
   //funcao construtor da classe 
   function cl_obrasalvara() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("obrasalvara"); 
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
       $this->ob04_codobra = ($this->ob04_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_codobra"]:$this->ob04_codobra);
       $this->ob04_alvara = ($this->ob04_alvara == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_alvara"]:$this->ob04_alvara);
       if($this->ob04_data == ""){
         $this->ob04_data_dia = ($this->ob04_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_data_dia"]:$this->ob04_data_dia);
         $this->ob04_data_mes = ($this->ob04_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_data_mes"]:$this->ob04_data_mes);
         $this->ob04_data_ano = ($this->ob04_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_data_ano"]:$this->ob04_data_ano);
         if($this->ob04_data_dia != ""){
            $this->ob04_data = $this->ob04_data_ano."-".$this->ob04_data_mes."-".$this->ob04_data_dia;
         }
       }
       $this->ob04_processo = ($this->ob04_processo == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_processo"]:$this->ob04_processo);
       $this->ob04_titularprocesso = ($this->ob04_titularprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_titularprocesso"]:$this->ob04_titularprocesso);
       if($this->ob04_dtprocesso == ""){
         $this->ob04_dtprocesso_dia = ($this->ob04_dtprocesso_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_dtprocesso_dia"]:$this->ob04_dtprocesso_dia);
         $this->ob04_dtprocesso_mes = ($this->ob04_dtprocesso_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_dtprocesso_mes"]:$this->ob04_dtprocesso_mes);
         $this->ob04_dtprocesso_ano = ($this->ob04_dtprocesso_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_dtprocesso_ano"]:$this->ob04_dtprocesso_ano);
         if($this->ob04_dtprocesso_dia != ""){
            $this->ob04_dtprocesso = $this->ob04_dtprocesso_ano."-".$this->ob04_dtprocesso_mes."-".$this->ob04_dtprocesso_dia;
         }
       }
       $this->ob04_obsprocesso = ($this->ob04_obsprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_obsprocesso"]:$this->ob04_obsprocesso);
       if($this->ob04_dtvalidade == ""){
         $this->ob04_dtvalidade_dia = ($this->ob04_dtvalidade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_dtvalidade_dia"]:$this->ob04_dtvalidade_dia);
         $this->ob04_dtvalidade_mes = ($this->ob04_dtvalidade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_dtvalidade_mes"]:$this->ob04_dtvalidade_mes);
         $this->ob04_dtvalidade_ano = ($this->ob04_dtvalidade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_dtvalidade_ano"]:$this->ob04_dtvalidade_ano);
         if($this->ob04_dtvalidade_dia != ""){
            $this->ob04_dtvalidade = $this->ob04_dtvalidade_ano."-".$this->ob04_dtvalidade_mes."-".$this->ob04_dtvalidade_dia;
         }
       }
       if($this->ob04_dataexpedicao == ""){
         $this->ob04_dataexpedicao_dia = ($this->ob04_dataexpedicao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_dataexpedicao_dia"]:$this->ob04_dataexpedicao_dia);
         $this->ob04_dataexpedicao_mes = ($this->ob04_dataexpedicao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_dataexpedicao_mes"]:$this->ob04_dataexpedicao_mes);
         $this->ob04_dataexpedicao_ano = ($this->ob04_dataexpedicao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_dataexpedicao_ano"]:$this->ob04_dataexpedicao_ano);
         if($this->ob04_dataexpedicao_dia != ""){
            $this->ob04_dataexpedicao = $this->ob04_dataexpedicao_ano."-".$this->ob04_dataexpedicao_mes."-".$this->ob04_dataexpedicao_dia;
         }
       }
     }else{
       $this->ob04_codobra = ($this->ob04_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob04_codobra"]:$this->ob04_codobra);
     }
   }
   // funcao para inclusao
   function incluir ($ob04_codobra){ 
      $this->atualizacampos();
     if($this->ob04_alvara == null ){ 
       $this->erro_sql = " Campo Alvará não informado.";
       $this->erro_campo = "ob04_alvara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob04_data == null ){ 
       $this->erro_sql = " Campo Data do Alvará não informado.";
       $this->erro_campo = "ob04_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob04_dtprocesso == null ){ 
       $this->ob04_dtprocesso = "null";
     }
     if($this->ob04_dtvalidade == null ){ 
       $this->erro_sql = " Campo Data Validade Alvará não informado.";
       $this->erro_campo = "ob04_dtvalidade_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ob04_alvara == "" || $ob04_alvara == null ){
       $result = db_query("select nextval('obrasalvara_ob04_alvara_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: obrasalvara_ob04_alvara_seq do campo: ob04_alvara"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ob04_alvara = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from obrasalvara_ob04_alvara_seq");
       if(($result != false) && (pg_result($result,0,0) < $ob04_alvara)){
         $this->erro_sql = " Campo ob04_alvara maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ob04_alvara = $ob04_alvara; 
       }
     }
     if(($this->ob04_codobra == null) || ($this->ob04_codobra == "") ){ 
       $this->erro_sql = " Campo ob04_codobra nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into obrasalvara(
                                       ob04_codobra 
                                      ,ob04_alvara 
                                      ,ob04_data 
                                      ,ob04_processo 
                                      ,ob04_titularprocesso 
                                      ,ob04_dtprocesso 
                                      ,ob04_obsprocesso 
                                      ,ob04_dtvalidade 
                                      ,ob04_dataexpedicao 
                       )
                values (
                                $this->ob04_codobra 
                               ,$this->ob04_alvara 
                               ,".($this->ob04_data == "null" || $this->ob04_data == ""?"null":"'".$this->ob04_data."'")." 
                               ,'$this->ob04_processo' 
                               ,'$this->ob04_titularprocesso' 
                               ,".($this->ob04_dtprocesso == "null" || $this->ob04_dtprocesso == ""?"null":"'".$this->ob04_dtprocesso."'")." 
                               ,'$this->ob04_obsprocesso' 
                               ,".($this->ob04_dtvalidade == "null" || $this->ob04_dtvalidade == ""?"null":"'".$this->ob04_dtvalidade."'")." 
                               ,".($this->ob04_dataexpedicao == "null" || $this->ob04_dataexpedicao == ""?"null":"'".$this->ob04_dataexpedicao."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "alvara da obra ($this->ob04_codobra) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "alvara da obra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "alvara da obra ($this->ob04_codobra) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob04_codobra;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ob04_codobra  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5917,'$this->ob04_codobra','I')");
         $resac = db_query("insert into db_acount values($acount,949,5917,'','".AddSlashes(pg_result($resaco,0,'ob04_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,949,5918,'','".AddSlashes(pg_result($resaco,0,'ob04_alvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,949,5919,'','".AddSlashes(pg_result($resaco,0,'ob04_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,949,18640,'','".AddSlashes(pg_result($resaco,0,'ob04_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,949,18641,'','".AddSlashes(pg_result($resaco,0,'ob04_titularprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,949,18642,'','".AddSlashes(pg_result($resaco,0,'ob04_dtprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,949,18643,'','".AddSlashes(pg_result($resaco,0,'ob04_obsprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,949,18644,'','".AddSlashes(pg_result($resaco,0,'ob04_dtvalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,949,20461,'','".AddSlashes(pg_result($resaco,0,'ob04_dataexpedicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ob04_codobra=null) { 
      $this->atualizacampos();
     $sql = " update obrasalvara set ";
     $virgula = "";
     if(trim($this->ob04_codobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob04_codobra"])){ 
       $sql  .= $virgula." ob04_codobra = $this->ob04_codobra ";
       $virgula = ",";
       if(trim($this->ob04_codobra) == null ){ 
         $this->erro_sql = " Campo Código da Obra não informado.";
         $this->erro_campo = "ob04_codobra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob04_alvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob04_alvara"])){ 
       $sql  .= $virgula." ob04_alvara = $this->ob04_alvara ";
       $virgula = ",";
       if(trim($this->ob04_alvara) == null ){ 
         $this->erro_sql = " Campo Alvará não informado.";
         $this->erro_campo = "ob04_alvara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob04_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob04_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob04_data_dia"] !="") ){ 
       $sql  .= $virgula." ob04_data = '$this->ob04_data' ";
       $virgula = ",";
       if(trim($this->ob04_data) == null ){ 
         $this->erro_sql = " Campo Data do Alvará não informado.";
         $this->erro_campo = "ob04_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob04_data_dia"])){ 
         $sql  .= $virgula." ob04_data = null ";
         $virgula = ",";
         if(trim($this->ob04_data) == null ){ 
           $this->erro_sql = " Campo Data do Alvará não informado.";
           $this->erro_campo = "ob04_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ob04_processo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob04_processo"])){ 
       $sql  .= $virgula." ob04_processo = '$this->ob04_processo' ";
       $virgula = ",";
     }
     if(trim($this->ob04_titularprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob04_titularprocesso"])){ 
       $sql  .= $virgula." ob04_titularprocesso = '$this->ob04_titularprocesso' ";
       $virgula = ",";
     }
     if(trim($this->ob04_dtprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob04_dtprocesso_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob04_dtprocesso_dia"] !="") ){ 
       $sql  .= $virgula." ob04_dtprocesso = '$this->ob04_dtprocesso' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob04_dtprocesso_dia"])){ 
         $sql  .= $virgula." ob04_dtprocesso = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ob04_obsprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob04_obsprocesso"])){ 
       $sql  .= $virgula." ob04_obsprocesso = '$this->ob04_obsprocesso' ";
       $virgula = ",";
     }
     if(trim($this->ob04_dtvalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob04_dtvalidade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob04_dtvalidade_dia"] !="") ){ 
       $sql  .= $virgula." ob04_dtvalidade = '$this->ob04_dtvalidade' ";
       $virgula = ",";
       if(trim($this->ob04_dtvalidade) == null ){ 
         $this->erro_sql = " Campo Data Validade Alvará não informado.";
         $this->erro_campo = "ob04_dtvalidade_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob04_dtvalidade_dia"])){ 
         $sql  .= $virgula." ob04_dtvalidade = null ";
         $virgula = ",";
         if(trim($this->ob04_dtvalidade) == null ){ 
           $this->erro_sql = " Campo Data Validade Alvará não informado.";
           $this->erro_campo = "ob04_dtvalidade_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ob04_dataexpedicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob04_dataexpedicao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob04_dataexpedicao_dia"] !="") ){ 
       $sql  .= $virgula." ob04_dataexpedicao = '$this->ob04_dataexpedicao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob04_dataexpedicao_dia"])){ 
         $sql  .= $virgula." ob04_dataexpedicao = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($ob04_codobra!=null){
       $sql .= " ob04_codobra = $this->ob04_codobra";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ob04_codobra));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5917,'$this->ob04_codobra','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ob04_codobra"]) || $this->ob04_codobra != "")
             $resac = db_query("insert into db_acount values($acount,949,5917,'".AddSlashes(pg_result($resaco,$conresaco,'ob04_codobra'))."','$this->ob04_codobra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ob04_alvara"]) || $this->ob04_alvara != "")
             $resac = db_query("insert into db_acount values($acount,949,5918,'".AddSlashes(pg_result($resaco,$conresaco,'ob04_alvara'))."','$this->ob04_alvara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ob04_data"]) || $this->ob04_data != "")
             $resac = db_query("insert into db_acount values($acount,949,5919,'".AddSlashes(pg_result($resaco,$conresaco,'ob04_data'))."','$this->ob04_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ob04_processo"]) || $this->ob04_processo != "")
             $resac = db_query("insert into db_acount values($acount,949,18640,'".AddSlashes(pg_result($resaco,$conresaco,'ob04_processo'))."','$this->ob04_processo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ob04_titularprocesso"]) || $this->ob04_titularprocesso != "")
             $resac = db_query("insert into db_acount values($acount,949,18641,'".AddSlashes(pg_result($resaco,$conresaco,'ob04_titularprocesso'))."','$this->ob04_titularprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ob04_dtprocesso"]) || $this->ob04_dtprocesso != "")
             $resac = db_query("insert into db_acount values($acount,949,18642,'".AddSlashes(pg_result($resaco,$conresaco,'ob04_dtprocesso'))."','$this->ob04_dtprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ob04_obsprocesso"]) || $this->ob04_obsprocesso != "")
             $resac = db_query("insert into db_acount values($acount,949,18643,'".AddSlashes(pg_result($resaco,$conresaco,'ob04_obsprocesso'))."','$this->ob04_obsprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ob04_dtvalidade"]) || $this->ob04_dtvalidade != "")
             $resac = db_query("insert into db_acount values($acount,949,18644,'".AddSlashes(pg_result($resaco,$conresaco,'ob04_dtvalidade'))."','$this->ob04_dtvalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ob04_dataexpedicao"]) || $this->ob04_dataexpedicao != "")
             $resac = db_query("insert into db_acount values($acount,949,20461,'".AddSlashes(pg_result($resaco,$conresaco,'ob04_dataexpedicao'))."','$this->ob04_dataexpedicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "alvara da obra nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob04_codobra;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "alvara da obra nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob04_codobra;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob04_codobra;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ob04_codobra=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ob04_codobra));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,5917,'$ob04_codobra','E')");
           $resac  = db_query("insert into db_acount values($acount,949,5917,'','".AddSlashes(pg_result($resaco,$iresaco,'ob04_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,949,5918,'','".AddSlashes(pg_result($resaco,$iresaco,'ob04_alvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,949,5919,'','".AddSlashes(pg_result($resaco,$iresaco,'ob04_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,949,18640,'','".AddSlashes(pg_result($resaco,$iresaco,'ob04_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,949,18641,'','".AddSlashes(pg_result($resaco,$iresaco,'ob04_titularprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,949,18642,'','".AddSlashes(pg_result($resaco,$iresaco,'ob04_dtprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,949,18643,'','".AddSlashes(pg_result($resaco,$iresaco,'ob04_obsprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,949,18644,'','".AddSlashes(pg_result($resaco,$iresaco,'ob04_dtvalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,949,20461,'','".AddSlashes(pg_result($resaco,$iresaco,'ob04_dataexpedicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from obrasalvara
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ob04_codobra != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ob04_codobra = $ob04_codobra ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "alvara da obra nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ob04_codobra;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "alvara da obra nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ob04_codobra;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ob04_codobra;
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
        $this->erro_sql   = "Record Vazio na Tabela:obrasalvara";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ob04_codobra=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasalvara ";
     $sql .= "      inner join obras  on  obras.ob01_codobra = obrasalvara.ob04_codobra";
     $sql .= "      inner join obrastiporesp  on  obrastiporesp.ob02_cod = obras.ob01_tiporesp";
     $sql .= "      inner join obrasresp     on obrasresp.ob10_codobra = obras.ob01_codobra       ";
     $sql .= "      inner join cgm           on cgm.z01_numcgm         = obrasresp.ob10_numcgm    ";
      
     $sql2 = "";
     if($dbwhere==""){
       if($ob04_codobra!=null ){
         $sql2 .= " where obrasalvara.ob04_codobra = $ob04_codobra "; 
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
   // funcao do sql 
   function sql_query_file ( $ob04_codobra=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasalvara ";
     $sql2 = "";
     if($dbwhere==""){
       if($ob04_codobra!=null ){
         $sql2 .= " where obrasalvara.ob04_codobra = $ob04_codobra "; 
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
   function sql_query_txt ( $ob04_codobra=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasalvara ";
     $sql .= "      inner join obras         on  obras.ob01_codobra         = obrasalvara.ob04_codobra";
     $sql .= "      inner join obrasconstr   on  obrasconstr.ob08_codobra   = obras.ob01_codobra";
     $sql .= "      inner join obrashabite   on  obrashabite.ob09_codconstr = obrasconstr.ob08_codconstr";
     $sql .= "      inner join obrastiporesp on  obrastiporesp.ob02_cod = obras.ob01_tiporesp";
     $sql .= "      inner join obraspropri   on ob03_codobra = ob04_codobra";
     $sql .= "      inner join cgm           on z01_numcgm  = obraspropri.ob03_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($ob04_codobra!=null ){
         $sql2 .= " where obrasalvara.ob04_codobra = $ob04_codobra "; 
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
   function sql_queryobras ( $ob04_codobra=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasalvara ";
     $sql .= "      inner join obras         on  obras.ob01_codobra         = obrasalvara.ob04_codobra";
     $sql .= "      inner join obrasconstr   on  obrasconstr.ob08_codobra   = obras.ob01_codobra";
     $sql2 = "";
     if($dbwhere==""){
       if($ob04_codobra!=null ){
         $sql2 .= " where obrasalvara.ob04_codobra = $ob04_codobra "; 
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
   function sql_query_obrasalvara($iCodigoObra) {
                                                                                                                        
    $sSql  = "select ob04_codobra,                                                                                                        ";
    $sSql .= "       ob04_alvara,                                                                                                         ";
    $sSql .= "       ob04_data,                                                                                                           ";
    $sSql .= "       ob04_processo,                                                                                                       ";
    $sSql .= "       ob04_titularprocesso,                                                                                                ";
    $sSql .= "       ob04_dtprocesso,                                                                                                     ";
    $sSql .= "       ob04_obsprocesso,                                                                                                    ";
    $sSql .= "       ob04_dataexpedicao,                                                                                                  ";
    $sSql .= "       ob04_dtvalidade,                                                                                                     ";
    $sSql .= "       ob26_sequencial,                                                                                                     ";
    $sSql .= "       ob26_obrasalvara,                                                                                                    ";
    $sSql .= "       ob26_protprocesso,                                                                                                   ";
    $sSql .= "       case when ob26_protprocesso is null                                                                                  ";
    $sSql .= "         then false                                                                                                         ";
    $sSql .= "         else true                                                                                                          ";
    $sSql .= "       end as ob04_processosistema,                                                                                         ";
    $sSql .= "       p58_codproc,                                                                                                         ";
    $sSql .= "       p58_requer                                                                                                           ";
    $sSql .= "                                                                                                                            ";
    $sSql .= "  from obrasalvara                                                                                                          ";
    $sSql .= " inner join obras                    on obras.ob01_codobra                        = obrasalvara.ob04_codobra                ";
    $sSql .= " inner join obrastiporesp           on obrastiporesp.ob02_cod                   = obras.ob01_tiporesp                       ";
    $sSql .= "  left join obrasalvaraprotprocesso on obrasalvaraprotprocesso.ob26_obrasalvara = obrasalvara.ob04_codobra                  ";
    $sSql .= "  left join protprocesso            on protprocesso.p58_codproc                 = obrasalvaraprotprocesso.ob26_protprocesso ";
    $sSql .= " where ob04_codobra = {$iCodigoObra}                                                                                        ";
    
    return $sSql;
    
  }
  
   /**
   * Busca obras conforme a matricula do imovel indicada
   * @param integer $iMatricula
   * @return string
   */
  function sql_query_obrasCadastroImobiliario($iMatricula) {

    $sSql = "select ob01_codobra,                                                         ";
    $sSql.= "       ano_alvara,                                                           ";
    $sSql.= "       ob01_nomeobra,                                                        ";
    $sSql.= "       ob04_alvara,                                                          ";
    $sSql.= "       (ob08_area - area_ocupada) as ob08_area,                              ";
    $sSql.= "       ob08_codconstr,                                                       ";
    $sSql.= "       ob07_pavimentos,                                                      ";
    $sSql.= "       ob07_lograd,                                                          ";
    $sSql.= "       ob07_numero,                                                          ";
    $sSql.= "       ob07_compl,                                                           ";
    $sSql.= "       ob24_iptubase                                                         ";
    $sSql.= "  from (select distinct                                                      ";
    $sSql.= "               (select coalesce( sum(j39_area), '0')                         ";
    $sSql.= "                  from iptuconstrobrasconstr                                 ";
    $sSql.= "                       inner join iptuconstr   on j39_matric = j132_matric   ";
    $sSql.= "                                              and j39_idcons = j132_idconstr ";
    $sSql.= "                 where j132_obrasconstr = obrasconstr.ob08_codconstr         ";
    $sSql.= "               )                              as area_ocupada,               ";
    $sSql.= "               extract(year from ob04_data)   as ano_alvara,                 ";
    $sSql.= "               ob01_codobra,                                                 ";
    $sSql.= "               ob01_nomeobra,                                                ";
    $sSql.= "               ob04_alvara,                                                  ";
    $sSql.= "               ob08_area,                                                    ";
    $sSql.= "               ob08_codconstr,                                               ";
    $sSql.= "               coalesce(ob07_pavimentos, '0') as ob07_pavimentos,            ";
    $sSql.= "               ob07_lograd,                                                  ";
    $sSql.= "               ob07_numero,                                                  ";
    $sSql.= "               ob07_compl,                                                   ";
    $sSql.= "               ob24_iptubase                                                 ";
    $sSql.= "        from iptubase a                                                      ";
    $sSql.= "             inner join lote           on j34_idbql      = a.j01_idbql       ";
    $sSql.= "             inner join iptubase b     on b.j01_idbql    = lote.j34_idbql    ";
    $sSql.= "             inner join obrasiptubase  on ob24_iptubase  = b.j01_matric      ";
    $sSql.= "             inner join obrasalvara    on ob04_codobra   = ob24_obras        ";
    $sSql.= "             inner join obras          on ob01_codobra   = ob24_obras        ";
    $sSql.= "               inner join obrasconstr    on ob08_codobra   = ob01_codobra    ";
    $sSql.= "               inner join obrasender     on ob07_codobra   = ob08_codobra    ";
    $sSql.= "                                        and ob07_codconstr = ob08_codconstr  ";
    $sSql.= "               inner join obrasiptubase  on ob24_obras     = ob01_codobra    ";
    $sSql.= "               inner join iptubase a     on a.j01_matric   = ob24_iptubase   ";
    $sSql.= "               inner join iptubase b     on b.j01_idbql    = a.j01_idbql     ";
    $sSql.= "         where a.j01_matric = {$iMatricula}                                  ";
    $sSql.= "        ) as query                                                           ";
    $sSql.= "  where (ob08_area - area_ocupada) > 0                                       ";
    return $sSql;
  }
   function sql_query_obrasalvaras_relatorio($sCampos = "*", $sWhere, $lHabite = null, $sOrderBy = null) {
  	
    $sSql  = "select distinct {$sCampos}                                                                  \n";
    $sSql .= "  from obrasalvara                                                                    \n";
    $sSql .= " inner join obrasconstr   on obrasconstr  .ob08_codobra = obrasalvara.ob04_codobra    \n";
    $sSql .= " inner join obraspropri   on obraspropri  .ob03_codobra = obrasalvara.ob04_codobra    \n";
    $sSql .= " inner join obrasender    on obrasender.ob07_codconstr  = obrasconstr.ob08_codconstr  \n";
    $sSql .= " inner join cgm           on cgm          .z01_numcgm   = obraspropri.ob03_numcgm     \n";
    $sSql .= " left  join obrasiptubase on obrasiptubase.ob24_obras   = obrasalvara.ob04_codobra    \n";
    $sSql .= " left  join obraslote     on obraslote    .ob05_codobra = obrasalvara.ob04_codobra    \n";
    $sSql .= " left  join lote          on lote         .j34_idbql    = obraslote  .ob05_idbql      \n";
    $sSql .= " left  join setor         on setor        .j30_codi     = lote       .j34_setor       \n";  

    if(is_bool($lHabite)) {
    	if ($lHabite) {
    		
    		$sSql .= " inner join ";
    		
    	} else {
    		
    		$sSql .= " left join ";
    		
    	}
    	
    	$sSql .= " obrashabite on obrashabite.ob09_codconstr = obrasconstr.ob08_codconstr \n"; 
    	
    } 
    
    if ($sWhere != '') {
    	
    	$sSql .= "where {$sWhere} ";
    	
    }
    
    if (!is_null($sOrderBy)) {
    	
    	$sSql .= " order by {$sOrderBy} "; 
    	
    }
  	
    return $sSql;
  	
  }
  
   function sql_query_relatorioObrasAlvara( $sWhere, $sCampos = "*", $sOrderBy = null ) {
    
    if ( !empty($sWhere) ) {
      $sWhere = " where " . $sWhere;
    }
    $sSql = " select {$sCampos}                                                                                      \n";     
    $sSql.= "   from obrasalvara                                                                                     \n";
    $sSql.= "        inner join obras                  on obras.ob01_codobra           = obrasalvara.ob04_codobra    \n";
    $sSql.= "        inner join obrasconstr            on obrasconstr.ob08_codobra     = obras.ob01_codobra          \n";
    $sSql.= "        inner join obrasender             on obrasender.ob07_codconstr    = obrasconstr.ob08_codconstr  \n";
    $sSql.= "                                         and obrasender.ob07_codobra      = obrasconstr.ob08_codobra    \n";
    $sSql.= "        inner join bairro                 on bairro.j13_codi              = obrasender.ob07_bairro      \n";
    $sSql.= "        left  join ruas                   on ruas.j14_codigo              = obrasender.ob07_lograd      \n";
    $sSql.= "        inner join obrastiporesp          on obrastiporesp.ob02_cod       = obras.ob01_tiporesp         \n";
    $sSql.= "        inner join obrasresp              on obrasresp.ob10_codobra       = obras.ob01_codobra          \n";
    $sSql.= "        inner join obrastecnicos          on obrastecnicos.ob20_codobra   = obras.ob01_codobra          \n";
    $sSql.= "        inner join obrastec               on obrastec.ob15_sequencial     = obrastecnicos.ob20_obrastec \n";
    $sSql.= "        inner join cgm as cgm_responsavel on cgm_responsavel.z01_numcgm   = obrasresp.ob10_numcgm       \n";
    $sSql.= "        inner join cgm as cgm_tecnico     on cgm_tecnico.z01_numcgm       = obrastec.ob15_numcgm        \n";
    $sSql.= " {$sWhere}                                                                                              \n";
    
    if ( !empty($sOrderBy) ) {
      $sSql .=" order by {$sOrderBy}    ";
    }
    return $sSql;  
  }
  
  function sql_query_cartaAlvara($sCampos, $iCodigoObra) {
  
  	$sSql  = "select {$sCampos}                                                                                                           ";
  	$sSql .= "  from obrasalvara                                                                                                          ";
  	$sSql .= " inner join obras         	        on obras.ob01_codobra          		          = obrasalvara.ob04_codobra                  ";
  	$sSql .= " inner join obrastiporesp 	        on obrastiporesp.ob02_cod      		          = obras.ob01_tiporesp                       ";
  	$sSql .= " inner join obrasresp               on obrasresp.ob10_codobra      		          = obras.ob01_codobra                        ";
  	$sSql .= " inner join cgm           	        on cgm.z01_numcgm              	            = obrasresp.ob10_numcgm                     ";
  	$sSql .= " left  join obrasiptubase 	        on obrasiptubase.ob24_obras    		          = obras.ob01_codobra                        ";
  	$sSql .= " left  join iptubase 	              on iptubase.j01_matric       		            = obrasiptubase.ob24_iptubase               ";
  	$sSql .= " left  join obrasalvaraprotprocesso on obrasalvaraprotprocesso.ob26_obrasalvara = obras.ob01_codobra                        ";
  	$sSql .= " left  join protprocesso            on protprocesso.p58_codproc                 = obrasalvaraprotprocesso.ob26_protprocesso ";
  	$sSql .= " left  join loteloc                 on loteloc.j06_idbql                        = iptubase.j01_idbql                        ";
  	$sSql .= " left  join setorloc                on setorloc.j05_codigo                      = loteloc.j06_setorloc                      ";
  	$sSql .= " where obras.ob01_codobra = {$iCodigoObra}                                                                                  ";
  
  
  	return $sSql;
  	 
  }
  
   /**
   * Incluir obraalvara setando alvara, o metodo incluir pega nextval ou last_val
   * 
   * @param integer $ob04_codobra
   * @return boolean
   */
  function incluirAlvara ($ob04_codobra){
  	$this->atualizacampos();
  	 
  	if($this->ob04_data == null ){
  		$this->erro_sql = " Campo Data do alvará nao Informado.";
  		$this->erro_campo = "ob04_data_dia";
  		$this->erro_banco = "";
  		$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
  		$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
  		$this->erro_status = "0";
  		return false;
  	}
  	if($this->ob04_dtprocesso == null ){
  		$this->ob04_dtprocesso = "null";
  	}
  	if($this->ob04_dtvalidade == null ){
  		$this->ob04_dtvalidade = "null";
  	}
  	
  	if(empty($this->ob04_alvara)){

  		$result = db_query("select nextval('obrasalvara_ob04_alvara_seq')");
  		if($result==false){
  			
  			$this->erro_banco = str_replace("\n","",@pg_last_error());
  			$this->erro_sql   = "Verifique o cadastro da sequencia: obrasalvara_ob04_alvara_seq do campo: ob04_alvara";
  			$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
  			$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
  			$this->erro_status = "0";
  			return false;
  		}

  		$this->ob04_alvara = pg_result($result,0,0);
  	}

  	/**
  	 * Valida se código do alvará ja está cadastrado
  	 */
    if(!empty($this->ob04_alvara)) {
    	$rsAlvara = db_query("select ob04_codobra from obrasalvara where ob04_alvara = {$this->ob04_alvara}");
    	if (pg_num_rows($rsAlvara) > 0) {
    		
    		$this->erro_msg    = "Código do alvará já registrado para a obra ". pg_result($rsAlvara,0,0);
    		$this->erro_status = "0";
    		return false;
    	}
    }
  	
  	if(($this->ob04_codobra == null) || ($this->ob04_codobra == "") ){
  		$this->erro_sql = " Campo ob04_codobra nao declarado.";
  		$this->erro_banco = "Chave Primaria zerada.";
  		$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
  		$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
  		$this->erro_status = "0";
  		return false;
  	}
  	
    /**
     * Na inclusão do alvará, a data de expedicao fica igual a data do alvará.
     */
    $this->ob04_dataexpedicao = date('Y-m-d', db_getsession('DB_datausu'));

  	$sql = "insert into obrasalvara(ob04_codobra,
  	                                ob04_alvara,
  	                                ob04_data,
  	                                ob04_processo,
  	                                ob04_titularprocesso,
  	                                ob04_dtprocesso,
  	                                ob04_obsprocesso,
  	                                ob04_dtvalidade,
                                    ob04_dataexpedicao)
                  values ($this->ob04_codobra,
                          $this->ob04_alvara,
                          ".($this->ob04_data == "null" || $this->ob04_data == ""?"null":"'".$this->ob04_data."'").",
                          '$this->ob04_processo',
                          '$this->ob04_titularprocesso',
                          ".($this->ob04_dtprocesso == "null" || $this->ob04_dtprocesso == ""?"null":"'".$this->ob04_dtprocesso."'").",
                          '$this->ob04_obsprocesso',
                          ".($this->ob04_dtvalidade == "null" || $this->ob04_dtvalidade == ""?"null":"'".$this->ob04_dtvalidade."'").",
                          ".($this->ob04_dataexpedicao == "null" || $this->ob04_dataexpedicao == ""?"null":"'".$this->ob04_dataexpedicao."'")."
            )";
  	$result = db_query($sql);
  	if($result==false){
  		$this->erro_banco = str_replace("\n","",@pg_last_error());
  		if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
  			$this->erro_sql   = "alvara da obra ($this->ob04_codobra) nao Incluído. Inclusao Abortada.";
  			$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
  			$this->erro_banco = "alvara da obra já Cadastrado";
  			$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
  		}else{
  			$this->erro_sql   = "alvara da obra ($this->ob04_codobra) nao Incluído. Inclusao Abortada.";
  			$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
  			$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
  		}
  		$this->erro_status = "0";
  		$this->numrows_incluir= 0;
  		return false;
  	}
  	$this->erro_banco = "";
  	$this->erro_sql = "Inclusao efetuada com Sucesso\\n";
  	$this->erro_sql .= "Valores : ".$this->ob04_codobra;
  	$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
  	$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
  	$this->erro_status = "1";
  	$this->numrows_incluir= pg_affected_rows($result);
  	$resaco = $this->sql_record($this->sql_query_file($this->ob04_codobra));
  	if(($resaco!=false)||($this->numrows!=0)){
  		$resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
  		$acount = pg_result($resac,0,0);
  		$resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
  		$resac = db_query("insert into db_acountkey values($acount,5917,'$this->ob04_codobra','I')");
  		$resac = db_query("insert into db_acount values($acount,949,5917,'','".AddSlashes(pg_result($resaco,0,'ob04_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
  		$resac = db_query("insert into db_acount values($acount,949,5918,'','".AddSlashes(pg_result($resaco,0,'ob04_alvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
  		$resac = db_query("insert into db_acount values($acount,949,5919,'','".AddSlashes(pg_result($resaco,0,'ob04_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
  		$resac = db_query("insert into db_acount values($acount,949,18640,'','".AddSlashes(pg_result($resaco,0,'ob04_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
  		$resac = db_query("insert into db_acount values($acount,949,18641,'','".AddSlashes(pg_result($resaco,0,'ob04_titularprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
  		$resac = db_query("insert into db_acount values($acount,949,18642,'','".AddSlashes(pg_result($resaco,0,'ob04_dtprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
  		$resac = db_query("insert into db_acount values($acount,949,18643,'','".AddSlashes(pg_result($resaco,0,'ob04_obsprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
  		$resac = db_query("insert into db_acount values($acount,949,18644,'','".AddSlashes(pg_result($resaco,0,'ob04_dtvalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,949,20461,'','".AddSlashes(pg_result($resaco,0,'ob04_dataexpedicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
  	}
  	return true;
  }
}
?>