<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: vacinas
//CLASSE DA ENTIDADE vac_devolucao
class cl_vac_devolucao { 
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
   var $vc26_i_codigo = 0; 
   var $vc26_i_fechamento = 0; 
   var $vc26_i_usuario = 0; 
   var $vc26_d_data_dia = null; 
   var $vc26_d_data_mes = null; 
   var $vc26_d_data_ano = null; 
   var $vc26_d_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 vc26_i_codigo = int4 = Código 
                 vc26_i_fechamento = int4 = Fechamento 
                 vc26_i_usuario = int4 = Usuario 
                 vc26_d_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_vac_devolucao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vac_devolucao"); 
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
       $this->vc26_i_codigo = ($this->vc26_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc26_i_codigo"]:$this->vc26_i_codigo);
       $this->vc26_i_fechamento = ($this->vc26_i_fechamento == ""?@$GLOBALS["HTTP_POST_VARS"]["vc26_i_fechamento"]:$this->vc26_i_fechamento);
       $this->vc26_i_usuario = ($this->vc26_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["vc26_i_usuario"]:$this->vc26_i_usuario);
       if($this->vc26_d_data == ""){
         $this->vc26_d_data_dia = ($this->vc26_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["vc26_d_data_dia"]:$this->vc26_d_data_dia);
         $this->vc26_d_data_mes = ($this->vc26_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["vc26_d_data_mes"]:$this->vc26_d_data_mes);
         $this->vc26_d_data_ano = ($this->vc26_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["vc26_d_data_ano"]:$this->vc26_d_data_ano);
         if($this->vc26_d_data_dia != ""){
            $this->vc26_d_data = $this->vc26_d_data_ano."-".$this->vc26_d_data_mes."-".$this->vc26_d_data_dia;
         }
       }
     }else{
       $this->vc26_i_codigo = ($this->vc26_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc26_i_codigo"]:$this->vc26_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($vc26_i_codigo){ 
      $this->atualizacampos();
     if($this->vc26_i_fechamento == null ){ 
       $this->erro_sql = " Campo Fechamento nao Informado.";
       $this->erro_campo = "vc26_i_fechamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc26_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "vc26_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc26_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "vc26_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($vc26_i_codigo == "" || $vc26_i_codigo == null ){
       $result = db_query("select nextval('vac_devolucao_vc26_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vac_devolucao_vc26_i_codigo_seq do campo: vc26_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->vc26_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vac_devolucao_vc26_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $vc26_i_codigo)){
         $this->erro_sql = " Campo vc26_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->vc26_i_codigo = $vc26_i_codigo; 
       }
     }
     if(($this->vc26_i_codigo == null) || ($this->vc26_i_codigo == "") ){ 
       $this->erro_sql = " Campo vc26_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vac_devolucao(
                                       vc26_i_codigo 
                                      ,vc26_i_fechamento 
                                      ,vc26_i_usuario 
                                      ,vc26_d_data 
                       )
                values (
                                $this->vc26_i_codigo 
                               ,$this->vc26_i_fechamento 
                               ,$this->vc26_i_usuario 
                               ,".($this->vc26_d_data == "null" || $this->vc26_d_data == ""?"null":"'".$this->vc26_d_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Devolução de fechamento ($this->vc26_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Devolução de fechamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Devolução de fechamento ($this->vc26_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc26_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->vc26_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17570,'$this->vc26_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3103,17570,'','".AddSlashes(pg_result($resaco,0,'vc26_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3103,17571,'','".AddSlashes(pg_result($resaco,0,'vc26_i_fechamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3103,17573,'','".AddSlashes(pg_result($resaco,0,'vc26_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3103,17572,'','".AddSlashes(pg_result($resaco,0,'vc26_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($vc26_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vac_devolucao set ";
     $virgula = "";
     if(trim($this->vc26_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc26_i_codigo"])){ 
       $sql  .= $virgula." vc26_i_codigo = $this->vc26_i_codigo ";
       $virgula = ",";
       if(trim($this->vc26_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "vc26_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc26_i_fechamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc26_i_fechamento"])){ 
       $sql  .= $virgula." vc26_i_fechamento = $this->vc26_i_fechamento ";
       $virgula = ",";
       if(trim($this->vc26_i_fechamento) == null ){ 
         $this->erro_sql = " Campo Fechamento nao Informado.";
         $this->erro_campo = "vc26_i_fechamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc26_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc26_i_usuario"])){ 
       $sql  .= $virgula." vc26_i_usuario = $this->vc26_i_usuario ";
       $virgula = ",";
       if(trim($this->vc26_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "vc26_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc26_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc26_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["vc26_d_data_dia"] !="") ){ 
       $sql  .= $virgula." vc26_d_data = '$this->vc26_d_data' ";
       $virgula = ",";
       if(trim($this->vc26_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "vc26_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["vc26_d_data_dia"])){ 
         $sql  .= $virgula." vc26_d_data = null ";
         $virgula = ",";
         if(trim($this->vc26_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "vc26_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($vc26_i_codigo!=null){
       $sql .= " vc26_i_codigo = $this->vc26_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->vc26_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17570,'$this->vc26_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc26_i_codigo"]) || $this->vc26_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3103,17570,'".AddSlashes(pg_result($resaco,$conresaco,'vc26_i_codigo'))."','$this->vc26_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc26_i_fechamento"]) || $this->vc26_i_fechamento != "")
           $resac = db_query("insert into db_acount values($acount,3103,17571,'".AddSlashes(pg_result($resaco,$conresaco,'vc26_i_fechamento'))."','$this->vc26_i_fechamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc26_i_usuario"]) || $this->vc26_i_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3103,17573,'".AddSlashes(pg_result($resaco,$conresaco,'vc26_i_usuario'))."','$this->vc26_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc26_d_data"]) || $this->vc26_d_data != "")
           $resac = db_query("insert into db_acount values($acount,3103,17572,'".AddSlashes(pg_result($resaco,$conresaco,'vc26_d_data'))."','$this->vc26_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Devolução de fechamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc26_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Devolução de fechamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc26_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc26_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($vc26_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($vc26_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17570,'$vc26_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3103,17570,'','".AddSlashes(pg_result($resaco,$iresaco,'vc26_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3103,17571,'','".AddSlashes(pg_result($resaco,$iresaco,'vc26_i_fechamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3103,17573,'','".AddSlashes(pg_result($resaco,$iresaco,'vc26_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3103,17572,'','".AddSlashes(pg_result($resaco,$iresaco,'vc26_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vac_devolucao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($vc26_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " vc26_i_codigo = $vc26_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Devolução de fechamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$vc26_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Devolução de fechamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$vc26_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$vc26_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vac_devolucao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $vc26_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_devolucao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vac_devolucao.vc26_i_usuario";
     $sql .= "      inner join vac_fechamento  on  vac_fechamento.vc20_i_codigo = vac_devolucao.vc26_i_fechamento";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vac_fechamento.vc20_i_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($vc26_i_codigo!=null ){
         $sql2 .= " where vac_devolucao.vc26_i_codigo = $vc26_i_codigo "; 
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
   function sql_query_file ( $vc26_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_devolucao ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc26_i_codigo!=null ){
         $sql2 .= " where vac_devolucao.vc26_i_codigo = $vc26_i_codigo "; 
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
function sql_query2 ( $vc26_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_devolucao ";
     $sql .= "      inner join db_usuarios         on  id_usuario        = vac_devolucao.vc26_i_usuario";
     $sql .= "      inner join vac_fechamento      on  vc20_i_codigo     = vac_devolucao.vc26_i_fechamento";
     $sql .= "      left join vac_devfechaaplica   on  vc24_i_devolucao  = vac_devolucao.vc26_i_codigo";
     $sql .= "      left join vac_fechaaplica      on  vc21_i_codigo     = vac_devfechaaplica.vc24_i_fechaaplica";
     $sql .= "      left join vac_aplicalote       on  vc17_i_codigo     = vac_fechaaplica.vc21_i_aplicalote";
     $sql .= "      left join vac_devfechadescarte on  vc25_i_devolucao  = vac_devolucao.vc26_i_codigo";
     $sql .= "      left join vac_fechadescarte    on  vc22_i_codigo     = vac_devfechadescarte.vc25_i_fechadescarte";
     $sql .= "      left join vac_descarte         on  vc19_i_codigo     = vac_fechadescarte.vc22_i_descarte";
     $sql2 = "";
     if($dbwhere==""){
       if($vc26_i_codigo!=null ){
         $sql2 .= " where vac_devolucao.vc26_i_codigo = $vc26_i_codigo "; 
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