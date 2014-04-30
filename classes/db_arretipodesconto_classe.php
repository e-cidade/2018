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

//MODULO: caixa
//CLASSE DA ENTIDADE arretipodesconto
class cl_arretipodesconto { 
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
   var $k19_tipo = 0; 
   var $k19_dtini_dia = null; 
   var $k19_dtini_mes = null; 
   var $k19_dtini_ano = null; 
   var $k19_dtini = null; 
   var $k19_dtfim_dia = null; 
   var $k19_dtfim_mes = null; 
   var $k19_dtfim_ano = null; 
   var $k19_dtfim = null; 
   var $k19_percjuros = 0; 
   var $k19_percmulta = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k19_tipo = int4 = tipo de debito 
                 k19_dtini = date = Data Inicial 
                 k19_dtfim = date = Data Final 
                 k19_percjuros = float8 = Percentual de desconto nos juros 
                 k19_percmulta = float8 = Percentual de desconto na multa 
                 ";
   //funcao construtor da classe 
   function cl_arretipodesconto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arretipodesconto"); 
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
       $this->k19_tipo = ($this->k19_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k19_tipo"]:$this->k19_tipo);
       if($this->k19_dtini == ""){
         $this->k19_dtini_dia = ($this->k19_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k19_dtini_dia"]:$this->k19_dtini_dia);
         $this->k19_dtini_mes = ($this->k19_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k19_dtini_mes"]:$this->k19_dtini_mes);
         $this->k19_dtini_ano = ($this->k19_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k19_dtini_ano"]:$this->k19_dtini_ano);
         if($this->k19_dtini_dia != ""){
            $this->k19_dtini = $this->k19_dtini_ano."-".$this->k19_dtini_mes."-".$this->k19_dtini_dia;
         }
       }
       if($this->k19_dtfim == ""){
         $this->k19_dtfim_dia = ($this->k19_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k19_dtfim_dia"]:$this->k19_dtfim_dia);
         $this->k19_dtfim_mes = ($this->k19_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k19_dtfim_mes"]:$this->k19_dtfim_mes);
         $this->k19_dtfim_ano = ($this->k19_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k19_dtfim_ano"]:$this->k19_dtfim_ano);
         if($this->k19_dtfim_dia != ""){
            $this->k19_dtfim = $this->k19_dtfim_ano."-".$this->k19_dtfim_mes."-".$this->k19_dtfim_dia;
         }
       }
       $this->k19_percjuros = ($this->k19_percjuros == ""?@$GLOBALS["HTTP_POST_VARS"]["k19_percjuros"]:$this->k19_percjuros);
       $this->k19_percmulta = ($this->k19_percmulta == ""?@$GLOBALS["HTTP_POST_VARS"]["k19_percmulta"]:$this->k19_percmulta);
     }else{
       $this->k19_tipo = ($this->k19_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k19_tipo"]:$this->k19_tipo);
     }
   }
   // funcao para inclusao
   function incluir ($k19_tipo){ 
      $this->atualizacampos();
     if($this->k19_dtini == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "k19_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k19_dtfim == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "k19_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k19_percjuros == null ){ 
       $this->erro_sql = " Campo Percentual de desconto nos juros nao Informado.";
       $this->erro_campo = "k19_percjuros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k19_percmulta == null ){ 
       $this->erro_sql = " Campo Percentual de desconto na multa nao Informado.";
       $this->erro_campo = "k19_percmulta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->k19_tipo = $k19_tipo; 
     if(($this->k19_tipo == null) || ($this->k19_tipo == "") ){ 
       $this->erro_sql = " Campo k19_tipo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arretipodesconto(
                                       k19_tipo 
                                      ,k19_dtini 
                                      ,k19_dtfim 
                                      ,k19_percjuros 
                                      ,k19_percmulta 
                       )
                values (
                                $this->k19_tipo 
                               ,".($this->k19_dtini == "null" || $this->k19_dtini == ""?"null":"'".$this->k19_dtini."'")." 
                               ,".($this->k19_dtfim == "null" || $this->k19_dtfim == ""?"null":"'".$this->k19_dtfim."'")." 
                               ,$this->k19_percjuros 
                               ,$this->k19_percmulta 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Descontos por arretipo ($this->k19_tipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Descontos por arretipo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Descontos por arretipo ($this->k19_tipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k19_tipo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k19_tipo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7321,'$this->k19_tipo','I')");
       $resac = db_query("insert into db_acount values($acount,1216,7321,'','".AddSlashes(pg_result($resaco,0,'k19_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1216,7322,'','".AddSlashes(pg_result($resaco,0,'k19_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1216,7323,'','".AddSlashes(pg_result($resaco,0,'k19_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1216,7324,'','".AddSlashes(pg_result($resaco,0,'k19_percjuros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1216,7325,'','".AddSlashes(pg_result($resaco,0,'k19_percmulta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k19_tipo=null) { 
      $this->atualizacampos();
     $sql = " update arretipodesconto set ";
     $virgula = "";
     if(trim($this->k19_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k19_tipo"])){ 
       $sql  .= $virgula." k19_tipo = $this->k19_tipo ";
       $virgula = ",";
       if(trim($this->k19_tipo) == null ){ 
         $this->erro_sql = " Campo tipo de debito nao Informado.";
         $this->erro_campo = "k19_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k19_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k19_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k19_dtini_dia"] !="") ){ 
       $sql  .= $virgula." k19_dtini = '$this->k19_dtini' ";
       $virgula = ",";
       if(trim($this->k19_dtini) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "k19_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k19_dtini_dia"])){ 
         $sql  .= $virgula." k19_dtini = null ";
         $virgula = ",";
         if(trim($this->k19_dtini) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "k19_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k19_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k19_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k19_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." k19_dtfim = '$this->k19_dtfim' ";
       $virgula = ",";
       if(trim($this->k19_dtfim) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "k19_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k19_dtfim_dia"])){ 
         $sql  .= $virgula." k19_dtfim = null ";
         $virgula = ",";
         if(trim($this->k19_dtfim) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "k19_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k19_percjuros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k19_percjuros"])){ 
       $sql  .= $virgula." k19_percjuros = $this->k19_percjuros ";
       $virgula = ",";
       if(trim($this->k19_percjuros) == null ){ 
         $this->erro_sql = " Campo Percentual de desconto nos juros nao Informado.";
         $this->erro_campo = "k19_percjuros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k19_percmulta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k19_percmulta"])){ 
       $sql  .= $virgula." k19_percmulta = $this->k19_percmulta ";
       $virgula = ",";
       if(trim($this->k19_percmulta) == null ){ 
         $this->erro_sql = " Campo Percentual de desconto na multa nao Informado.";
         $this->erro_campo = "k19_percmulta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k19_tipo!=null){
       $sql .= " k19_tipo = $this->k19_tipo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k19_tipo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7321,'$this->k19_tipo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k19_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1216,7321,'".AddSlashes(pg_result($resaco,$conresaco,'k19_tipo'))."','$this->k19_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k19_dtini"]))
           $resac = db_query("insert into db_acount values($acount,1216,7322,'".AddSlashes(pg_result($resaco,$conresaco,'k19_dtini'))."','$this->k19_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k19_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,1216,7323,'".AddSlashes(pg_result($resaco,$conresaco,'k19_dtfim'))."','$this->k19_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k19_percjuros"]))
           $resac = db_query("insert into db_acount values($acount,1216,7324,'".AddSlashes(pg_result($resaco,$conresaco,'k19_percjuros'))."','$this->k19_percjuros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k19_percmulta"]))
           $resac = db_query("insert into db_acount values($acount,1216,7325,'".AddSlashes(pg_result($resaco,$conresaco,'k19_percmulta'))."','$this->k19_percmulta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Descontos por arretipo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k19_tipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Descontos por arretipo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k19_tipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k19_tipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k19_tipo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k19_tipo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7321,'$k19_tipo','E')");
         $resac = db_query("insert into db_acount values($acount,1216,7321,'','".AddSlashes(pg_result($resaco,$iresaco,'k19_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1216,7322,'','".AddSlashes(pg_result($resaco,$iresaco,'k19_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1216,7323,'','".AddSlashes(pg_result($resaco,$iresaco,'k19_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1216,7324,'','".AddSlashes(pg_result($resaco,$iresaco,'k19_percjuros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1216,7325,'','".AddSlashes(pg_result($resaco,$iresaco,'k19_percmulta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from arretipodesconto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k19_tipo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k19_tipo = $k19_tipo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Descontos por arretipo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k19_tipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Descontos por arretipo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k19_tipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k19_tipo;
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
        $this->erro_sql   = "Record Vazio na Tabela:arretipodesconto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>