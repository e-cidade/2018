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
//CLASSE DA ENTIDADE cadtipoparcdeb
class cl_cadtipoparcdeb { 
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
   var $k41_cadtipoparc = 0; 
   var $k41_arretipo = 0; 
   var $k41_vencini_dia = null; 
   var $k41_vencini_mes = null; 
   var $k41_vencini_ano = null; 
   var $k41_vencini = null; 
   var $k41_vencfim_dia = null; 
   var $k41_vencfim_mes = null; 
   var $k41_vencfim_ano = null; 
   var $k41_vencfim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k41_cadtipoparc = int4 = Código 
                 k41_arretipo = int4 = tipo de debito 
                 k41_vencini = date = Vencimento inicial 
                 k41_vencfim = date = Vencimento final 
                 ";
   //funcao construtor da classe 
   function cl_cadtipoparcdeb() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadtipoparcdeb"); 
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
       $this->k41_cadtipoparc = ($this->k41_cadtipoparc == ""?@$GLOBALS["HTTP_POST_VARS"]["k41_cadtipoparc"]:$this->k41_cadtipoparc);
       $this->k41_arretipo = ($this->k41_arretipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k41_arretipo"]:$this->k41_arretipo);
       if($this->k41_vencini == ""){
         $this->k41_vencini_dia = ($this->k41_vencini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k41_vencini_dia"]:$this->k41_vencini_dia);
         $this->k41_vencini_mes = ($this->k41_vencini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k41_vencini_mes"]:$this->k41_vencini_mes);
         $this->k41_vencini_ano = ($this->k41_vencini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k41_vencini_ano"]:$this->k41_vencini_ano);
         if($this->k41_vencini_dia != ""){
            $this->k41_vencini = $this->k41_vencini_ano."-".$this->k41_vencini_mes."-".$this->k41_vencini_dia;
         }
       }
       if($this->k41_vencfim == ""){
         $this->k41_vencfim_dia = ($this->k41_vencfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k41_vencfim_dia"]:$this->k41_vencfim_dia);
         $this->k41_vencfim_mes = ($this->k41_vencfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k41_vencfim_mes"]:$this->k41_vencfim_mes);
         $this->k41_vencfim_ano = ($this->k41_vencfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k41_vencfim_ano"]:$this->k41_vencfim_ano);
         if($this->k41_vencfim_dia != ""){
            $this->k41_vencfim = $this->k41_vencfim_ano."-".$this->k41_vencfim_mes."-".$this->k41_vencfim_dia;
         }
       }
     }else{
       $this->k41_cadtipoparc = ($this->k41_cadtipoparc == ""?@$GLOBALS["HTTP_POST_VARS"]["k41_cadtipoparc"]:$this->k41_cadtipoparc);
       $this->k41_arretipo = ($this->k41_arretipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k41_arretipo"]:$this->k41_arretipo);
     }
   }
   // funcao para inclusao
   function incluir ($k41_cadtipoparc,$k41_arretipo){ 
      $this->atualizacampos();
     if($this->k41_vencini == null ){ 
       $this->erro_sql = " Campo Vencimento inicial nao Informado.";
       $this->erro_campo = "k41_vencini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k41_vencfim == null ){ 
       $this->erro_sql = " Campo Vencimento final nao Informado.";
       $this->erro_campo = "k41_vencfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->k41_cadtipoparc = $k41_cadtipoparc; 
       $this->k41_arretipo = $k41_arretipo; 
     if(($this->k41_cadtipoparc == null) || ($this->k41_cadtipoparc == "") ){ 
       $this->erro_sql = " Campo k41_cadtipoparc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k41_arretipo == null) || ($this->k41_arretipo == "") ){ 
       $this->erro_sql = " Campo k41_arretipo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadtipoparcdeb(
                                       k41_cadtipoparc 
                                      ,k41_arretipo 
                                      ,k41_vencini 
                                      ,k41_vencfim 
                       )
                values (
                                $this->k41_cadtipoparc 
                               ,$this->k41_arretipo 
                               ,".($this->k41_vencini == "null" || $this->k41_vencini == ""?"null":"'".$this->k41_vencini."'")." 
                               ,".($this->k41_vencfim == "null" || $this->k41_vencfim == ""?"null":"'".$this->k41_vencfim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipos de débitos  que a regra de parcelamento usa ($this->k41_cadtipoparc."-".$this->k41_arretipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipos de débitos  que a regra de parcelamento usa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipos de débitos  que a regra de parcelamento usa ($this->k41_cadtipoparc."-".$this->k41_arretipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k41_cadtipoparc."-".$this->k41_arretipo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k41_cadtipoparc,$this->k41_arretipo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7579,'$this->k41_cadtipoparc','I')");
       $resac = db_query("insert into db_acountkey values($acount,7580,'$this->k41_arretipo','I')");
       $resac = db_query("insert into db_acount values($acount,1258,7579,'','".AddSlashes(pg_result($resaco,0,'k41_cadtipoparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1258,7580,'','".AddSlashes(pg_result($resaco,0,'k41_arretipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1258,9968,'','".AddSlashes(pg_result($resaco,0,'k41_vencini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1258,9969,'','".AddSlashes(pg_result($resaco,0,'k41_vencfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k41_cadtipoparc=null,$k41_arretipo=null) { 
      $this->atualizacampos();
     $sql = " update cadtipoparcdeb set ";
     $virgula = "";
     if(trim($this->k41_cadtipoparc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k41_cadtipoparc"])){ 
       $sql  .= $virgula." k41_cadtipoparc = $this->k41_cadtipoparc ";
       $virgula = ",";
       if(trim($this->k41_cadtipoparc) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "k41_cadtipoparc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k41_arretipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k41_arretipo"])){ 
       $sql  .= $virgula." k41_arretipo = $this->k41_arretipo ";
       $virgula = ",";
       if(trim($this->k41_arretipo) == null ){ 
         $this->erro_sql = " Campo tipo de debito nao Informado.";
         $this->erro_campo = "k41_arretipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k41_vencini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k41_vencini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k41_vencini_dia"] !="") ){ 
       $sql  .= $virgula." k41_vencini = '$this->k41_vencini' ";
       $virgula = ",";
       if(trim($this->k41_vencini) == null ){ 
         $this->erro_sql = " Campo Vencimento inicial nao Informado.";
         $this->erro_campo = "k41_vencini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k41_vencini_dia"])){ 
         $sql  .= $virgula." k41_vencini = null ";
         $virgula = ",";
         if(trim($this->k41_vencini) == null ){ 
           $this->erro_sql = " Campo Vencimento inicial nao Informado.";
           $this->erro_campo = "k41_vencini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k41_vencfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k41_vencfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k41_vencfim_dia"] !="") ){ 
       $sql  .= $virgula." k41_vencfim = '$this->k41_vencfim' ";
       $virgula = ",";
       if(trim($this->k41_vencfim) == null ){ 
         $this->erro_sql = " Campo Vencimento final nao Informado.";
         $this->erro_campo = "k41_vencfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k41_vencfim_dia"])){ 
         $sql  .= $virgula." k41_vencfim = null ";
         $virgula = ",";
         if(trim($this->k41_vencfim) == null ){ 
           $this->erro_sql = " Campo Vencimento final nao Informado.";
           $this->erro_campo = "k41_vencfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($k41_cadtipoparc!=null){
       $sql .= " k41_cadtipoparc = $this->k41_cadtipoparc";
     }
     if($k41_arretipo!=null){
       $sql .= " and  k41_arretipo = $this->k41_arretipo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k41_cadtipoparc,$this->k41_arretipo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7579,'$this->k41_cadtipoparc','A')");
         $resac = db_query("insert into db_acountkey values($acount,7580,'$this->k41_arretipo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k41_cadtipoparc"]))
           $resac = db_query("insert into db_acount values($acount,1258,7579,'".AddSlashes(pg_result($resaco,$conresaco,'k41_cadtipoparc'))."','$this->k41_cadtipoparc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k41_arretipo"]))
           $resac = db_query("insert into db_acount values($acount,1258,7580,'".AddSlashes(pg_result($resaco,$conresaco,'k41_arretipo'))."','$this->k41_arretipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k41_vencini"]))
           $resac = db_query("insert into db_acount values($acount,1258,9968,'".AddSlashes(pg_result($resaco,$conresaco,'k41_vencini'))."','$this->k41_vencini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k41_vencfim"]))
           $resac = db_query("insert into db_acount values($acount,1258,9969,'".AddSlashes(pg_result($resaco,$conresaco,'k41_vencfim'))."','$this->k41_vencfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de débitos  que a regra de parcelamento usa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k41_cadtipoparc."-".$this->k41_arretipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de débitos  que a regra de parcelamento usa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k41_cadtipoparc."-".$this->k41_arretipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k41_cadtipoparc."-".$this->k41_arretipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k41_cadtipoparc=null,$k41_arretipo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k41_cadtipoparc,$k41_arretipo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7579,'$k41_cadtipoparc','E')");
         $resac = db_query("insert into db_acountkey values($acount,7580,'$k41_arretipo','E')");
         $resac = db_query("insert into db_acount values($acount,1258,7579,'','".AddSlashes(pg_result($resaco,$iresaco,'k41_cadtipoparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1258,7580,'','".AddSlashes(pg_result($resaco,$iresaco,'k41_arretipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1258,9968,'','".AddSlashes(pg_result($resaco,$iresaco,'k41_vencini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1258,9969,'','".AddSlashes(pg_result($resaco,$iresaco,'k41_vencfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadtipoparcdeb
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k41_cadtipoparc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k41_cadtipoparc = $k41_cadtipoparc ";
        }
        if($k41_arretipo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k41_arretipo = $k41_arretipo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de débitos  que a regra de parcelamento usa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k41_cadtipoparc."-".$k41_arretipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de débitos  que a regra de parcelamento usa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k41_cadtipoparc."-".$k41_arretipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k41_cadtipoparc."-".$k41_arretipo;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadtipoparcdeb";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k41_cadtipoparc=null,$k41_arretipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadtipoparcdeb ";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = cadtipoparcdeb.k41_arretipo";
     $sql .= "      inner join cadtipoparc  on  cadtipoparc.k40_codigo = cadtipoparcdeb.k41_cadtipoparc";
     $sql .= "      inner join cadtipo  as a on   a.k03_tipo = arretipo.k03_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if($k41_cadtipoparc!=null ){
         $sql2 .= " where cadtipoparcdeb.k41_cadtipoparc = $k41_cadtipoparc "; 
       } 
       if($k41_arretipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cadtipoparcdeb.k41_arretipo = $k41_arretipo "; 
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
   function sql_query_file ( $k41_cadtipoparc=null,$k41_arretipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadtipoparcdeb ";
     $sql2 = "";
     if($dbwhere==""){
       if($k41_cadtipoparc!=null ){
         $sql2 .= " where cadtipoparcdeb.k41_cadtipoparc = $k41_cadtipoparc "; 
       } 
       if($k41_arretipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cadtipoparcdeb.k41_arretipo = $k41_arretipo "; 
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