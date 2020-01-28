<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE taxaservval
class cl_taxaservval { 
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
   var $cm35_sequencial = 0; 
   var $cm35_taxaserv = 0; 
   var $cm35_dataini_dia = null; 
   var $cm35_dataini_mes = null; 
   var $cm35_dataini_ano = null; 
   var $cm35_dataini = null; 
   var $cm35_datafin_dia = null; 
   var $cm35_datafin_mes = null; 
   var $cm35_datafin_ano = null; 
   var $cm35_datafin = null; 
   var $cm35_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cm35_sequencial = int4 = Sequencial 
                 cm35_taxaserv = int4 = Taxa Serviço 
                 cm35_dataini = date = Data Inicial 
                 cm35_datafin = date = Data Final 
                 cm35_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_taxaservval() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("taxaservval"); 
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
       $this->cm35_sequencial = ($this->cm35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cm35_sequencial"]:$this->cm35_sequencial);
       $this->cm35_taxaserv = ($this->cm35_taxaserv == ""?@$GLOBALS["HTTP_POST_VARS"]["cm35_taxaserv"]:$this->cm35_taxaserv);
       if($this->cm35_dataini == ""){
         $this->cm35_dataini_dia = ($this->cm35_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm35_dataini_dia"]:$this->cm35_dataini_dia);
         $this->cm35_dataini_mes = ($this->cm35_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm35_dataini_mes"]:$this->cm35_dataini_mes);
         $this->cm35_dataini_ano = ($this->cm35_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm35_dataini_ano"]:$this->cm35_dataini_ano);
         if($this->cm35_dataini_dia != ""){
            $this->cm35_dataini = $this->cm35_dataini_ano."-".$this->cm35_dataini_mes."-".$this->cm35_dataini_dia;
         }
       }
       if($this->cm35_datafin == ""){
         $this->cm35_datafin_dia = ($this->cm35_datafin_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm35_datafin_dia"]:$this->cm35_datafin_dia);
         $this->cm35_datafin_mes = ($this->cm35_datafin_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm35_datafin_mes"]:$this->cm35_datafin_mes);
         $this->cm35_datafin_ano = ($this->cm35_datafin_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm35_datafin_ano"]:$this->cm35_datafin_ano);
         if($this->cm35_datafin_dia != ""){
            $this->cm35_datafin = $this->cm35_datafin_ano."-".$this->cm35_datafin_mes."-".$this->cm35_datafin_dia;
         }
       }
       $this->cm35_valor = ($this->cm35_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["cm35_valor"]:$this->cm35_valor);
     }else{
       $this->cm35_sequencial = ($this->cm35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cm35_sequencial"]:$this->cm35_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($cm35_sequencial){ 
      $this->atualizacampos();
     if($this->cm35_taxaserv == null ){ 
       $this->erro_sql = " Campo Taxa Serviço nao Informado.";
       $this->erro_campo = "cm35_taxaserv";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm35_dataini == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "cm35_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm35_datafin == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "cm35_datafin_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm35_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "cm35_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cm35_sequencial == "" || $cm35_sequencial == null ){
       $result = db_query("select nextval('taxaservval_cm35_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: taxaservval_cm35_sequencial_seq do campo: cm35_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cm35_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from taxaservval_cm35_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm35_sequencial)){
         $this->erro_sql = " Campo cm35_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm35_sequencial = $cm35_sequencial; 
       }
     }
     if(($this->cm35_sequencial == null) || ($this->cm35_sequencial == "") ){ 
       $this->erro_sql = " Campo cm35_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into taxaservval(
                                       cm35_sequencial 
                                      ,cm35_taxaserv 
                                      ,cm35_dataini 
                                      ,cm35_datafin 
                                      ,cm35_valor 
                       )
                values (
                                $this->cm35_sequencial 
                               ,$this->cm35_taxaserv 
                               ,".($this->cm35_dataini == "null" || $this->cm35_dataini == ""?"null":"'".$this->cm35_dataini."'")." 
                               ,".($this->cm35_datafin == "null" || $this->cm35_datafin == ""?"null":"'".$this->cm35_datafin."'")." 
                               ,$this->cm35_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Taxa Serviço ($this->cm35_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Taxa Serviço já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Taxa Serviço ($this->cm35_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm35_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm35_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15581,'$this->cm35_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2732,15581,'','".AddSlashes(pg_result($resaco,0,'cm35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2732,15582,'','".AddSlashes(pg_result($resaco,0,'cm35_taxaserv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2732,15583,'','".AddSlashes(pg_result($resaco,0,'cm35_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2732,15584,'','".AddSlashes(pg_result($resaco,0,'cm35_datafin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2732,15585,'','".AddSlashes(pg_result($resaco,0,'cm35_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cm35_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update taxaservval set ";
     $virgula = "";
     if(trim($this->cm35_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm35_sequencial"])){ 
       $sql  .= $virgula." cm35_sequencial = $this->cm35_sequencial ";
       $virgula = ",";
       if(trim($this->cm35_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "cm35_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm35_taxaserv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm35_taxaserv"])){ 
       $sql  .= $virgula." cm35_taxaserv = $this->cm35_taxaserv ";
       $virgula = ",";
       if(trim($this->cm35_taxaserv) == null ){ 
         $this->erro_sql = " Campo Taxa Serviço nao Informado.";
         $this->erro_campo = "cm35_taxaserv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm35_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm35_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm35_dataini_dia"] !="") ){ 
       $sql  .= $virgula." cm35_dataini = '$this->cm35_dataini' ";
       $virgula = ",";
       if(trim($this->cm35_dataini) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "cm35_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm35_dataini_dia"])){ 
         $sql  .= $virgula." cm35_dataini = null ";
         $virgula = ",";
         if(trim($this->cm35_dataini) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "cm35_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm35_datafin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm35_datafin_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm35_datafin_dia"] !="") ){ 
       $sql  .= $virgula." cm35_datafin = '$this->cm35_datafin' ";
       $virgula = ",";
       if(trim($this->cm35_datafin) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "cm35_datafin_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm35_datafin_dia"])){ 
         $sql  .= $virgula." cm35_datafin = null ";
         $virgula = ",";
         if(trim($this->cm35_datafin) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "cm35_datafin_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm35_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm35_valor"])){ 
       $sql  .= $virgula." cm35_valor = $this->cm35_valor ";
       $virgula = ",";
       if(trim($this->cm35_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "cm35_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cm35_sequencial!=null){
       $sql .= " cm35_sequencial = $this->cm35_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm35_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15581,'$this->cm35_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm35_sequencial"]) || $this->cm35_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2732,15581,'".AddSlashes(pg_result($resaco,$conresaco,'cm35_sequencial'))."','$this->cm35_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm35_taxaserv"]) || $this->cm35_taxaserv != "")
           $resac = db_query("insert into db_acount values($acount,2732,15582,'".AddSlashes(pg_result($resaco,$conresaco,'cm35_taxaserv'))."','$this->cm35_taxaserv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm35_dataini"]) || $this->cm35_dataini != "")
           $resac = db_query("insert into db_acount values($acount,2732,15583,'".AddSlashes(pg_result($resaco,$conresaco,'cm35_dataini'))."','$this->cm35_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm35_datafin"]) || $this->cm35_datafin != "")
           $resac = db_query("insert into db_acount values($acount,2732,15584,'".AddSlashes(pg_result($resaco,$conresaco,'cm35_datafin'))."','$this->cm35_datafin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm35_valor"]) || $this->cm35_valor != "")
           $resac = db_query("insert into db_acount values($acount,2732,15585,'".AddSlashes(pg_result($resaco,$conresaco,'cm35_valor'))."','$this->cm35_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Taxa Serviço nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm35_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Taxa Serviço nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cm35_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm35_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15581,'$cm35_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2732,15581,'','".AddSlashes(pg_result($resaco,$iresaco,'cm35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2732,15582,'','".AddSlashes(pg_result($resaco,$iresaco,'cm35_taxaserv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2732,15583,'','".AddSlashes(pg_result($resaco,$iresaco,'cm35_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2732,15584,'','".AddSlashes(pg_result($resaco,$iresaco,'cm35_datafin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2732,15585,'','".AddSlashes(pg_result($resaco,$iresaco,'cm35_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from taxaservval
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm35_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm35_sequencial = $cm35_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Taxa Serviço nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm35_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Taxa Serviço nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm35_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:taxaservval";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cm35_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from taxaservval ";
     $sql .= "      inner join taxaserv  on  taxaserv.cm11_i_codigo = taxaservval.cm35_taxaserv";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = taxaserv.cm11_i_historico";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = taxaserv.cm11_i_receita";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = taxaserv.cm11_i_tipo";
     $sql .= "      inner join procdiver  on  procdiver.dv09_procdiver = taxaserv.cm11_i_proced";
     $sql2 = "";
     if($dbwhere==""){
       if($cm35_sequencial!=null ){
         $sql2 .= " where taxaservval.cm35_sequencial = $cm35_sequencial "; 
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
   function sql_query_file ( $cm35_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from taxaservval ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm35_sequencial!=null ){
         $sql2 .= " where taxaservval.cm35_sequencial = $cm35_sequencial "; 
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