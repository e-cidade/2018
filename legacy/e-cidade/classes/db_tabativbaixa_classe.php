<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: issqn
//CLASSE DA ENTIDADE tabativbaixa
class cl_tabativbaixa { 
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
   var $q11_inscr = 0; 
   var $q11_seq = 0; 
   var $q11_processo = 0; 
   var $q11_oficio = 'f'; 
   var $q11_obs = null; 
   var $q11_login = 0; 
   var $q11_data_dia = null; 
   var $q11_data_mes = null; 
   var $q11_data_ano = null; 
   var $q11_data = null; 
   var $q11_hora = null; 
   var $q11_numero = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q11_inscr = int4 = inscricao 
                 q11_seq = int4 = sequencia 
                 q11_processo = int4 = Processo do protocolo 
                 q11_oficio = bool = Tipo de baixa 
                 q11_obs = text = Observação 
                 q11_login = int4 = Cod. Usuário 
                 q11_data = date = Data 
                 q11_hora = char(5) = Hora 
                 q11_numero = varchar(10) = q11_numero 
                 ";
   //funcao construtor da classe 
   function cl_tabativbaixa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tabativbaixa"); 
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
       $this->q11_inscr = ($this->q11_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_inscr"]:$this->q11_inscr);
       $this->q11_seq = ($this->q11_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_seq"]:$this->q11_seq);
       $this->q11_processo = ($this->q11_processo == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_processo"]:$this->q11_processo);
       $this->q11_oficio = ($this->q11_oficio == "f"?@$GLOBALS["HTTP_POST_VARS"]["q11_oficio"]:$this->q11_oficio);
       $this->q11_obs = ($this->q11_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_obs"]:$this->q11_obs);
       $this->q11_login = ($this->q11_login == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_login"]:$this->q11_login);
       if($this->q11_data == ""){
         $this->q11_data_dia = ($this->q11_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_data_dia"]:$this->q11_data_dia);
         $this->q11_data_mes = ($this->q11_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_data_mes"]:$this->q11_data_mes);
         $this->q11_data_ano = ($this->q11_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_data_ano"]:$this->q11_data_ano);
         if($this->q11_data_dia != ""){
            $this->q11_data = $this->q11_data_ano."-".$this->q11_data_mes."-".$this->q11_data_dia;
         }
       }
       $this->q11_hora = ($this->q11_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_hora"]:$this->q11_hora);
       $this->q11_numero = ($this->q11_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_numero"]:$this->q11_numero);
     }else{
       $this->q11_inscr = ($this->q11_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_inscr"]:$this->q11_inscr);
       $this->q11_seq = ($this->q11_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_seq"]:$this->q11_seq);
     }
   }
   // funcao para inclusao
   function incluir ($q11_inscr,$q11_seq){ 
      $this->atualizacampos();
     if($this->q11_processo == null ){ 
       $this->q11_processo = "0";
     }
     if($this->q11_oficio == null ){ 
       $this->erro_sql = " Campo Tipo de baixa nao Informado.";
       $this->erro_campo = "q11_oficio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q11_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "q11_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q11_login == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "q11_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q11_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "q11_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q11_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "q11_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
/*     if($this->q11_numero == null ){ 
       $this->erro_sql = " Campo q11_numero nao Informado.";
       $this->erro_campo = "q11_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }*/
       $this->q11_inscr = $q11_inscr; 
       $this->q11_seq = $q11_seq; 
     if(($this->q11_inscr == null) || ($this->q11_inscr == "") ){ 
       $this->erro_sql = " Campo q11_inscr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q11_seq == null) || ($this->q11_seq == "") ){ 
       $this->erro_sql = " Campo q11_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tabativbaixa(
                                       q11_inscr 
                                      ,q11_seq 
                                      ,q11_processo 
                                      ,q11_oficio 
                                      ,q11_obs 
                                      ,q11_login 
                                      ,q11_data 
                                      ,q11_hora 
                                      ,q11_numero 
                       )
                values (
                                $this->q11_inscr 
                               ,$this->q11_seq 
                               ,$this->q11_processo 
                               ,'$this->q11_oficio' 
                               ,'$this->q11_obs' 
                               ,$this->q11_login 
                               ,".($this->q11_data == "null" || $this->q11_data == ""?"null":"'".$this->q11_data."'")." 
                               ,'$this->q11_hora' 
                               ,'$this->q11_numero' 
                      )";

     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tabativbaixa ($this->q11_inscr."-".$this->q11_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tabativbaixa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tabativbaixa ($this->q11_inscr."-".$this->q11_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q11_inscr."-".$this->q11_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q11_inscr,$this->q11_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,298,'$this->q11_inscr','I')");
       $resac = db_query("insert into db_acountkey values($acount,299,'$this->q11_seq','I')");
       $resac = db_query("insert into db_acount values($acount,390,298,'','".AddSlashes(pg_result($resaco,0,'q11_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,390,299,'','".AddSlashes(pg_result($resaco,0,'q11_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,390,2408,'','".AddSlashes(pg_result($resaco,0,'q11_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,390,2409,'','".AddSlashes(pg_result($resaco,0,'q11_oficio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,390,6849,'','".AddSlashes(pg_result($resaco,0,'q11_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,390,6850,'','".AddSlashes(pg_result($resaco,0,'q11_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,390,6851,'','".AddSlashes(pg_result($resaco,0,'q11_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,390,6852,'','".AddSlashes(pg_result($resaco,0,'q11_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,390,12477,'','".AddSlashes(pg_result($resaco,0,'q11_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q11_inscr=null,$q11_seq=null) { 
      $this->atualizacampos();
     $sql = " update tabativbaixa set ";
     $virgula = "";
     if(trim($this->q11_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q11_inscr"])){ 
       $sql  .= $virgula." q11_inscr = $this->q11_inscr ";
       $virgula = ",";
       if(trim($this->q11_inscr) == null ){ 
         $this->erro_sql = " Campo inscricao nao Informado.";
         $this->erro_campo = "q11_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q11_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q11_seq"])){ 
       $sql  .= $virgula." q11_seq = $this->q11_seq ";
       $virgula = ",";
       if(trim($this->q11_seq) == null ){ 
         $this->erro_sql = " Campo sequencia nao Informado.";
         $this->erro_campo = "q11_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q11_processo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q11_processo"])){ 
        if(trim($this->q11_processo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q11_processo"])){ 
           $this->q11_processo = "0" ; 
        } 
       $sql  .= $virgula." q11_processo = $this->q11_processo ";
       $virgula = ",";
     }
     if(trim($this->q11_oficio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q11_oficio"])){ 
       $sql  .= $virgula." q11_oficio = '$this->q11_oficio' ";
       $virgula = ",";
       if(trim($this->q11_oficio) == null ){ 
         $this->erro_sql = " Campo Tipo de baixa nao Informado.";
         $this->erro_campo = "q11_oficio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q11_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q11_obs"])){ 
       $sql  .= $virgula." q11_obs = '$this->q11_obs' ";
       $virgula = ",";
       if(trim($this->q11_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "q11_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q11_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q11_login"])){ 
       $sql  .= $virgula." q11_login = $this->q11_login ";
       $virgula = ",";
       if(trim($this->q11_login) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "q11_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q11_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q11_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q11_data_dia"] !="") ){ 
       $sql  .= $virgula." q11_data = '$this->q11_data' ";
       $virgula = ",";
       if(trim($this->q11_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "q11_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q11_data_dia"])){ 
         $sql  .= $virgula." q11_data = null ";
         $virgula = ",";
         if(trim($this->q11_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "q11_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q11_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q11_hora"])){ 
       $sql  .= $virgula." q11_hora = '$this->q11_hora' ";
       $virgula = ",";
       if(trim($this->q11_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "q11_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q11_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q11_numero"])){ 
       $sql  .= $virgula." q11_numero = '$this->q11_numero' ";
       $virgula = ",";
       if(trim($this->q11_numero) == null ){ 
         $this->erro_sql = " Campo q11_numero nao Informado.";
         $this->erro_campo = "q11_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q11_inscr!=null){
       $sql .= " q11_inscr = $this->q11_inscr";
     }
     if($q11_seq!=null){
       $sql .= " and  q11_seq = $this->q11_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q11_inscr,$this->q11_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,298,'$this->q11_inscr','A')");
         $resac = db_query("insert into db_acountkey values($acount,299,'$this->q11_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q11_inscr"]))
           $resac = db_query("insert into db_acount values($acount,390,298,'".AddSlashes(pg_result($resaco,$conresaco,'q11_inscr'))."','$this->q11_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q11_seq"]))
           $resac = db_query("insert into db_acount values($acount,390,299,'".AddSlashes(pg_result($resaco,$conresaco,'q11_seq'))."','$this->q11_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q11_processo"]))
           $resac = db_query("insert into db_acount values($acount,390,2408,'".AddSlashes(pg_result($resaco,$conresaco,'q11_processo'))."','$this->q11_processo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q11_oficio"]))
           $resac = db_query("insert into db_acount values($acount,390,2409,'".AddSlashes(pg_result($resaco,$conresaco,'q11_oficio'))."','$this->q11_oficio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q11_obs"]))
           $resac = db_query("insert into db_acount values($acount,390,6849,'".AddSlashes(pg_result($resaco,$conresaco,'q11_obs'))."','$this->q11_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q11_login"]))
           $resac = db_query("insert into db_acount values($acount,390,6850,'".AddSlashes(pg_result($resaco,$conresaco,'q11_login'))."','$this->q11_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q11_data"]))
           $resac = db_query("insert into db_acount values($acount,390,6851,'".AddSlashes(pg_result($resaco,$conresaco,'q11_data'))."','$this->q11_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q11_hora"]))
           $resac = db_query("insert into db_acount values($acount,390,6852,'".AddSlashes(pg_result($resaco,$conresaco,'q11_hora'))."','$this->q11_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q11_numero"]))
           $resac = db_query("insert into db_acount values($acount,390,12477,'".AddSlashes(pg_result($resaco,$conresaco,'q11_numero'))."','$this->q11_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tabativbaixa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q11_inscr."-".$this->q11_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tabativbaixa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q11_inscr."-".$this->q11_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q11_inscr."-".$this->q11_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q11_inscr=null,$q11_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q11_inscr,$q11_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,298,'$q11_inscr','E')");
         $resac = db_query("insert into db_acountkey values($acount,299,'$q11_seq','E')");
         $resac = db_query("insert into db_acount values($acount,390,298,'','".AddSlashes(pg_result($resaco,$iresaco,'q11_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,390,299,'','".AddSlashes(pg_result($resaco,$iresaco,'q11_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,390,2408,'','".AddSlashes(pg_result($resaco,$iresaco,'q11_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,390,2409,'','".AddSlashes(pg_result($resaco,$iresaco,'q11_oficio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,390,6849,'','".AddSlashes(pg_result($resaco,$iresaco,'q11_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,390,6850,'','".AddSlashes(pg_result($resaco,$iresaco,'q11_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,390,6851,'','".AddSlashes(pg_result($resaco,$iresaco,'q11_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,390,6852,'','".AddSlashes(pg_result($resaco,$iresaco,'q11_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,390,12477,'','".AddSlashes(pg_result($resaco,$iresaco,'q11_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tabativbaixa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q11_inscr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q11_inscr = $q11_inscr ";
        }
        if($q11_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q11_seq = $q11_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tabativbaixa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q11_inscr."-".$q11_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tabativbaixa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q11_inscr."-".$q11_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q11_inscr."-".$q11_seq;
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
        $this->erro_sql   = "Record Vazio na Tabela:tabativbaixa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q11_inscr=null,$q11_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabativbaixa ";
     $sql .= "      inner join tabativ  on  tabativ.q07_inscr = tabativbaixa.q11_inscr and  tabativ.q07_seq = tabativbaixa.q11_seq";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tabativbaixa.q11_login";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = tabativ.q07_inscr";
     $sql .= "      inner join ativid  on  ativid.q03_ativ = tabativ.q07_ativ";
     $sql2 = "";
     if($dbwhere==""){
       if($q11_inscr!=null ){
         $sql2 .= " where tabativbaixa.q11_inscr = $q11_inscr "; 
       } 
       if($q11_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " tabativbaixa.q11_seq = $q11_seq "; 
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
   function sql_query_file ( $q11_inscr=null,$q11_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabativbaixa ";
     $sql2 = "";
     if($dbwhere==""){
       if($q11_inscr!=null ){
         $sql2 .= " where tabativbaixa.q11_inscr = $q11_inscr "; 
       } 
       if($q11_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " tabativbaixa.q11_seq = $q11_seq "; 
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