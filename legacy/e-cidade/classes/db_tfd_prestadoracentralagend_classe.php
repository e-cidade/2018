<?php
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

//MODULO: TFD
//CLASSE DA ENTIDADE tfd_prestadoracentralagend
class cl_tfd_prestadoracentralagend { 
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
   var $tf10_i_codigo = 0; 
   var $tf10_i_prestadora = 0; 
   var $tf10_i_centralagend = 0; 
   var $tf10_d_validadeini_dia = null; 
   var $tf10_d_validadeini_mes = null; 
   var $tf10_d_validadeini_ano = null; 
   var $tf10_d_validadeini = null; 
   var $tf10_d_validadefim_dia = null; 
   var $tf10_d_validadefim_mes = null; 
   var $tf10_d_validadefim_ano = null; 
   var $tf10_d_validadefim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tf10_i_codigo = int4 = Código 
                 tf10_i_prestadora = int4 = Prestadora 
                 tf10_i_centralagend = int4 = Central de Agendamento 
                 tf10_d_validadeini = date = Início 
                 tf10_d_validadefim = date = Fim 
                 ";
   //funcao construtor da classe 
   function cl_tfd_prestadoracentralagend() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_prestadoracentralagend"); 
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
       $this->tf10_i_codigo = ($this->tf10_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf10_i_codigo"]:$this->tf10_i_codigo);
       $this->tf10_i_prestadora = ($this->tf10_i_prestadora == ""?@$GLOBALS["HTTP_POST_VARS"]["tf10_i_prestadora"]:$this->tf10_i_prestadora);
       $this->tf10_i_centralagend = ($this->tf10_i_centralagend == ""?@$GLOBALS["HTTP_POST_VARS"]["tf10_i_centralagend"]:$this->tf10_i_centralagend);
       if($this->tf10_d_validadeini == ""){
         $this->tf10_d_validadeini_dia = ($this->tf10_d_validadeini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf10_d_validadeini_dia"]:$this->tf10_d_validadeini_dia);
         $this->tf10_d_validadeini_mes = ($this->tf10_d_validadeini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf10_d_validadeini_mes"]:$this->tf10_d_validadeini_mes);
         $this->tf10_d_validadeini_ano = ($this->tf10_d_validadeini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf10_d_validadeini_ano"]:$this->tf10_d_validadeini_ano);
         if($this->tf10_d_validadeini_dia != ""){
            $this->tf10_d_validadeini = $this->tf10_d_validadeini_ano."-".$this->tf10_d_validadeini_mes."-".$this->tf10_d_validadeini_dia;
         }
       }
       if($this->tf10_d_validadefim == ""){
         $this->tf10_d_validadefim_dia = ($this->tf10_d_validadefim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf10_d_validadefim_dia"]:$this->tf10_d_validadefim_dia);
         $this->tf10_d_validadefim_mes = ($this->tf10_d_validadefim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf10_d_validadefim_mes"]:$this->tf10_d_validadefim_mes);
         $this->tf10_d_validadefim_ano = ($this->tf10_d_validadefim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf10_d_validadefim_ano"]:$this->tf10_d_validadefim_ano);
         if($this->tf10_d_validadefim_dia != ""){
            $this->tf10_d_validadefim = $this->tf10_d_validadefim_ano."-".$this->tf10_d_validadefim_mes."-".$this->tf10_d_validadefim_dia;
         }
       }
     }else{
       $this->tf10_i_codigo = ($this->tf10_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf10_i_codigo"]:$this->tf10_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf10_i_codigo){ 
      $this->atualizacampos();
     if($this->tf10_i_prestadora == null ){ 
       $this->erro_sql = " Campo Prestadora nao Informado.";
       $this->erro_campo = "tf10_i_prestadora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf10_i_centralagend == null ){ 
       $this->erro_sql = " Campo Central de Agendamento nao Informado.";
       $this->erro_campo = "tf10_i_centralagend";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf10_d_validadeini == null ){ 
       $this->erro_sql = " Campo Início nao Informado.";
       $this->erro_campo = "tf10_d_validadeini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf10_d_validadefim == null ){ 
       $this->tf10_d_validadefim = "null";
     }
     if($tf10_i_codigo == "" || $tf10_i_codigo == null ){
       $result = db_query("select nextval('tfd_prestadoracentralagend_tf10_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_prestadoracentralagend_tf10_i_codigo_seq do campo: tf10_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tf10_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tfd_prestadoracentralagend_tf10_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf10_i_codigo)){
         $this->erro_sql = " Campo tf10_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf10_i_codigo = $tf10_i_codigo; 
       }
     }
     if(($this->tf10_i_codigo == null) || ($this->tf10_i_codigo == "") ){ 
       $this->erro_sql = " Campo tf10_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_prestadoracentralagend(
                                       tf10_i_codigo 
                                      ,tf10_i_prestadora 
                                      ,tf10_i_centralagend 
                                      ,tf10_d_validadeini 
                                      ,tf10_d_validadefim 
                       )
                values (
                                $this->tf10_i_codigo 
                               ,$this->tf10_i_prestadora 
                               ,$this->tf10_i_centralagend 
                               ,".($this->tf10_d_validadeini == "null" || $this->tf10_d_validadeini == ""?"null":"'".$this->tf10_d_validadeini."'")." 
                               ,".($this->tf10_d_validadefim == "null" || $this->tf10_d_validadefim == ""?"null":"'".$this->tf10_d_validadefim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_prestadoracentralagend ($this->tf10_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_prestadoracentralagend já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_prestadoracentralagend ($this->tf10_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf10_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tf10_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16366,'$this->tf10_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2866,16366,'','".AddSlashes(pg_result($resaco,0,'tf10_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2866,16367,'','".AddSlashes(pg_result($resaco,0,'tf10_i_prestadora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2866,16368,'','".AddSlashes(pg_result($resaco,0,'tf10_i_centralagend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2866,16369,'','".AddSlashes(pg_result($resaco,0,'tf10_d_validadeini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2866,16370,'','".AddSlashes(pg_result($resaco,0,'tf10_d_validadefim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tf10_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tfd_prestadoracentralagend set ";
     $virgula = "";
     if(trim($this->tf10_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf10_i_codigo"])){ 
       $sql  .= $virgula." tf10_i_codigo = $this->tf10_i_codigo ";
       $virgula = ",";
       if(trim($this->tf10_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "tf10_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf10_i_prestadora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf10_i_prestadora"])){ 
       $sql  .= $virgula." tf10_i_prestadora = $this->tf10_i_prestadora ";
       $virgula = ",";
       if(trim($this->tf10_i_prestadora) == null ){ 
         $this->erro_sql = " Campo Prestadora nao Informado.";
         $this->erro_campo = "tf10_i_prestadora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf10_i_centralagend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf10_i_centralagend"])){ 
       $sql  .= $virgula." tf10_i_centralagend = $this->tf10_i_centralagend ";
       $virgula = ",";
       if(trim($this->tf10_i_centralagend) == null ){ 
         $this->erro_sql = " Campo Central de Agendamento nao Informado.";
         $this->erro_campo = "tf10_i_centralagend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf10_d_validadeini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf10_d_validadeini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf10_d_validadeini_dia"] !="") ){ 
       $sql  .= $virgula." tf10_d_validadeini = '$this->tf10_d_validadeini' ";
       $virgula = ",";
       if(trim($this->tf10_d_validadeini) == null ){ 
         $this->erro_sql = " Campo Início nao Informado.";
         $this->erro_campo = "tf10_d_validadeini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf10_d_validadeini_dia"])){ 
         $sql  .= $virgula." tf10_d_validadeini = null ";
         $virgula = ",";
         if(trim($this->tf10_d_validadeini) == null ){ 
           $this->erro_sql = " Campo Início nao Informado.";
           $this->erro_campo = "tf10_d_validadeini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf10_d_validadefim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf10_d_validadefim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf10_d_validadefim_dia"] !="") ){ 
       $sql  .= $virgula." tf10_d_validadefim = '$this->tf10_d_validadefim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf10_d_validadefim_dia"])){ 
         $sql  .= $virgula." tf10_d_validadefim = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($tf10_i_codigo!=null){
       $sql .= " tf10_i_codigo = $this->tf10_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tf10_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16366,'$this->tf10_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf10_i_codigo"]) || $this->tf10_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2866,16366,'".AddSlashes(pg_result($resaco,$conresaco,'tf10_i_codigo'))."','$this->tf10_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf10_i_prestadora"]) || $this->tf10_i_prestadora != "")
           $resac = db_query("insert into db_acount values($acount,2866,16367,'".AddSlashes(pg_result($resaco,$conresaco,'tf10_i_prestadora'))."','$this->tf10_i_prestadora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf10_i_centralagend"]) || $this->tf10_i_centralagend != "")
           $resac = db_query("insert into db_acount values($acount,2866,16368,'".AddSlashes(pg_result($resaco,$conresaco,'tf10_i_centralagend'))."','$this->tf10_i_centralagend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf10_d_validadeini"]) || $this->tf10_d_validadeini != "")
           $resac = db_query("insert into db_acount values($acount,2866,16369,'".AddSlashes(pg_result($resaco,$conresaco,'tf10_d_validadeini'))."','$this->tf10_d_validadeini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf10_d_validadefim"]) || $this->tf10_d_validadefim != "")
           $resac = db_query("insert into db_acount values($acount,2866,16370,'".AddSlashes(pg_result($resaco,$conresaco,'tf10_d_validadefim'))."','$this->tf10_d_validadefim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_prestadoracentralagend nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf10_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_prestadoracentralagend nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf10_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf10_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tf10_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tf10_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16366,'$tf10_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2866,16366,'','".AddSlashes(pg_result($resaco,$iresaco,'tf10_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2866,16367,'','".AddSlashes(pg_result($resaco,$iresaco,'tf10_i_prestadora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2866,16368,'','".AddSlashes(pg_result($resaco,$iresaco,'tf10_i_centralagend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2866,16369,'','".AddSlashes(pg_result($resaco,$iresaco,'tf10_d_validadeini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2866,16370,'','".AddSlashes(pg_result($resaco,$iresaco,'tf10_d_validadefim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tfd_prestadoracentralagend
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf10_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf10_i_codigo = $tf10_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_prestadoracentralagend nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf10_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_prestadoracentralagend nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf10_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf10_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tfd_prestadoracentralagend";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tf10_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_prestadoracentralagend ";
     $sql .= "      inner join tfd_centralagendamento  on  tfd_centralagendamento.tf09_i_codigo = tfd_prestadoracentralagend.tf10_i_centralagend";
     $sql .= "      inner join tfd_prestadora  on  tfd_prestadora.tf25_i_codigo = tfd_prestadoracentralagend.tf10_i_prestadora";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = tfd_centralagendamento.tf09_i_numcgm";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = tfd_prestadora.tf25_i_cgm";
     $sql .= "      inner join tfd_destino  on  tfd_destino.tf03_i_codigo = tfd_prestadora.tf25_i_destino";
     $sql2 = "";
     if($dbwhere==""){
       if($tf10_i_codigo!=null ){
         $sql2 .= " where tfd_prestadoracentralagend.tf10_i_codigo = $tf10_i_codigo "; 
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
   function sql_query_file ( $tf10_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_prestadoracentralagend ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf10_i_codigo!=null ){
         $sql2 .= " where tfd_prestadoracentralagend.tf10_i_codigo = $tf10_i_codigo "; 
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

  function sql_query_passagem_destino( $tf10_i_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

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
     $sql .= " from tfd_prestadoracentralagend ";
     $sql .= "      inner join tfd_centralagendamento on tfd_centralagendamento.tf09_i_codigo = tfd_prestadoracentralagend.tf10_i_centralagend";
     $sql .= "      inner join tfd_prestadora         on tfd_prestadora.tf25_i_codigo         = tfd_prestadoracentralagend.tf10_i_prestadora";
     $sql .= "      inner join cgm                    on cgm.z01_numcgm                       = tfd_centralagendamento.tf09_i_numcgm";
     $sql .= "      inner join cgm  as a              on a.z01_numcgm                         = tfd_prestadora.tf25_i_cgm";
     $sql .= "      inner join tfd_destino            on tfd_destino.tf03_i_codigo            = tfd_prestadora.tf25_i_destino";
     $sql .= "      left  join passagemdestino        on passagemdestino.tf37_destino         = tfd_destino.tf03_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($tf10_i_codigo!=null ){
         $sql2 .= " where tfd_prestadoracentralagend.tf10_i_codigo = $tf10_i_codigo ";
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