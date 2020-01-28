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
//CLASSE DA ENTIDADE vac_fechamento
class cl_vac_fechamento { 
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
   var $vc20_i_codigo = 0; 
   var $vc20_d_dataini_dia = null; 
   var $vc20_d_dataini_mes = null; 
   var $vc20_d_dataini_ano = null; 
   var $vc20_d_dataini = null; 
   var $vc20_d_datafim_dia = null; 
   var $vc20_d_datafim_mes = null; 
   var $vc20_d_datafim_ano = null; 
   var $vc20_d_datafim = null; 
   var $vc20_i_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 vc20_i_codigo = int4 = Código 
                 vc20_d_dataini = date = Inicio 
                 vc20_d_datafim = date = Fim 
                 vc20_i_usuario = int4 = Usuario 
                 ";
   //funcao construtor da classe 
   function cl_vac_fechamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vac_fechamento"); 
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
       $this->vc20_i_codigo = ($this->vc20_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc20_i_codigo"]:$this->vc20_i_codigo);
       if($this->vc20_d_dataini == ""){
         $this->vc20_d_dataini_dia = ($this->vc20_d_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["vc20_d_dataini_dia"]:$this->vc20_d_dataini_dia);
         $this->vc20_d_dataini_mes = ($this->vc20_d_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["vc20_d_dataini_mes"]:$this->vc20_d_dataini_mes);
         $this->vc20_d_dataini_ano = ($this->vc20_d_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["vc20_d_dataini_ano"]:$this->vc20_d_dataini_ano);
         if($this->vc20_d_dataini_dia != ""){
            $this->vc20_d_dataini = $this->vc20_d_dataini_ano."-".$this->vc20_d_dataini_mes."-".$this->vc20_d_dataini_dia;
         }
       }
       if($this->vc20_d_datafim == ""){
         $this->vc20_d_datafim_dia = ($this->vc20_d_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["vc20_d_datafim_dia"]:$this->vc20_d_datafim_dia);
         $this->vc20_d_datafim_mes = ($this->vc20_d_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["vc20_d_datafim_mes"]:$this->vc20_d_datafim_mes);
         $this->vc20_d_datafim_ano = ($this->vc20_d_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["vc20_d_datafim_ano"]:$this->vc20_d_datafim_ano);
         if($this->vc20_d_datafim_dia != ""){
            $this->vc20_d_datafim = $this->vc20_d_datafim_ano."-".$this->vc20_d_datafim_mes."-".$this->vc20_d_datafim_dia;
         }
       }
       $this->vc20_i_usuario = ($this->vc20_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["vc20_i_usuario"]:$this->vc20_i_usuario);
     }else{
       $this->vc20_i_codigo = ($this->vc20_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc20_i_codigo"]:$this->vc20_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($vc20_i_codigo){ 
      $this->atualizacampos();
     if($this->vc20_d_dataini == null ){ 
       $this->erro_sql = " Campo Inicio nao Informado.";
       $this->erro_campo = "vc20_d_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc20_d_datafim == null ){ 
       $this->erro_sql = " Campo Fim nao Informado.";
       $this->erro_campo = "vc20_d_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc20_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "vc20_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($vc20_i_codigo == "" || $vc20_i_codigo == null ){
       $result = db_query("select nextval('vac_fechamento_vc20_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vac_fechamento_vc20_i_codigo_seq do campo: vc20_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->vc20_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vac_fechamento_vc20_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $vc20_i_codigo)){
         $this->erro_sql = " Campo vc20_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->vc20_i_codigo = $vc20_i_codigo; 
       }
     }
     if(($this->vc20_i_codigo == null) || ($this->vc20_i_codigo == "") ){ 
       $this->erro_sql = " Campo vc20_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vac_fechamento(
                                       vc20_i_codigo 
                                      ,vc20_d_dataini 
                                      ,vc20_d_datafim 
                                      ,vc20_i_usuario 
                       )
                values (
                                $this->vc20_i_codigo 
                               ,".($this->vc20_d_dataini == "null" || $this->vc20_d_dataini == ""?"null":"'".$this->vc20_d_dataini."'")." 
                               ,".($this->vc20_d_datafim == "null" || $this->vc20_d_datafim == ""?"null":"'".$this->vc20_d_datafim."'")." 
                               ,$this->vc20_i_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Fechamento ($this->vc20_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Fechamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Fechamento ($this->vc20_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc20_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->vc20_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17510,'$this->vc20_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3093,17510,'','".AddSlashes(pg_result($resaco,0,'vc20_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3093,17511,'','".AddSlashes(pg_result($resaco,0,'vc20_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3093,17512,'','".AddSlashes(pg_result($resaco,0,'vc20_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3093,17513,'','".AddSlashes(pg_result($resaco,0,'vc20_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($vc20_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vac_fechamento set ";
     $virgula = "";
     if(trim($this->vc20_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc20_i_codigo"])){ 
       $sql  .= $virgula." vc20_i_codigo = $this->vc20_i_codigo ";
       $virgula = ",";
       if(trim($this->vc20_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "vc20_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc20_d_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc20_d_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["vc20_d_dataini_dia"] !="") ){ 
       $sql  .= $virgula." vc20_d_dataini = '$this->vc20_d_dataini' ";
       $virgula = ",";
       if(trim($this->vc20_d_dataini) == null ){ 
         $this->erro_sql = " Campo Inicio nao Informado.";
         $this->erro_campo = "vc20_d_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["vc20_d_dataini_dia"])){ 
         $sql  .= $virgula." vc20_d_dataini = null ";
         $virgula = ",";
         if(trim($this->vc20_d_dataini) == null ){ 
           $this->erro_sql = " Campo Inicio nao Informado.";
           $this->erro_campo = "vc20_d_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->vc20_d_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc20_d_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["vc20_d_datafim_dia"] !="") ){ 
       $sql  .= $virgula." vc20_d_datafim = '$this->vc20_d_datafim' ";
       $virgula = ",";
       if(trim($this->vc20_d_datafim) == null ){ 
         $this->erro_sql = " Campo Fim nao Informado.";
         $this->erro_campo = "vc20_d_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["vc20_d_datafim_dia"])){ 
         $sql  .= $virgula." vc20_d_datafim = null ";
         $virgula = ",";
         if(trim($this->vc20_d_datafim) == null ){ 
           $this->erro_sql = " Campo Fim nao Informado.";
           $this->erro_campo = "vc20_d_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->vc20_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc20_i_usuario"])){ 
       $sql  .= $virgula." vc20_i_usuario = $this->vc20_i_usuario ";
       $virgula = ",";
       if(trim($this->vc20_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "vc20_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($vc20_i_codigo!=null){
       $sql .= " vc20_i_codigo = $this->vc20_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->vc20_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17510,'$this->vc20_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc20_i_codigo"]) || $this->vc20_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3093,17510,'".AddSlashes(pg_result($resaco,$conresaco,'vc20_i_codigo'))."','$this->vc20_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc20_d_dataini"]) || $this->vc20_d_dataini != "")
           $resac = db_query("insert into db_acount values($acount,3093,17511,'".AddSlashes(pg_result($resaco,$conresaco,'vc20_d_dataini'))."','$this->vc20_d_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc20_d_datafim"]) || $this->vc20_d_datafim != "")
           $resac = db_query("insert into db_acount values($acount,3093,17512,'".AddSlashes(pg_result($resaco,$conresaco,'vc20_d_datafim'))."','$this->vc20_d_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc20_i_usuario"]) || $this->vc20_i_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3093,17513,'".AddSlashes(pg_result($resaco,$conresaco,'vc20_i_usuario'))."','$this->vc20_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fechamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc20_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fechamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc20_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc20_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($vc20_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($vc20_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17510,'$vc20_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3093,17510,'','".AddSlashes(pg_result($resaco,$iresaco,'vc20_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3093,17511,'','".AddSlashes(pg_result($resaco,$iresaco,'vc20_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3093,17512,'','".AddSlashes(pg_result($resaco,$iresaco,'vc20_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3093,17513,'','".AddSlashes(pg_result($resaco,$iresaco,'vc20_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vac_fechamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($vc20_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " vc20_i_codigo = $vc20_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fechamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$vc20_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fechamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$vc20_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$vc20_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vac_fechamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $vc20_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_fechamento ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vac_fechamento.vc20_i_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($vc20_i_codigo!=null ){
         $sql2 .= " where vac_fechamento.vc20_i_codigo = $vc20_i_codigo "; 
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
// funcao do sql2
   function sql_query2 ( $vc20_i_codigo=null,$campos="*",$ordem=null,$dbwhere="",$iLote = ""){ 
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
     $sql .= " from vac_fechamento ";
     $sql .= "   inner join db_usuarios             on id_usuario        = vac_fechamento.vc20_i_usuario";
     $sql .= "   left join vac_fechadescarte        on vc22_i_fechamento = vac_fechamento.vc20_i_codigo";
     $sql .= "   left join vac_descarte             on vc19_i_codigo     = vac_fechadescarte.vc22_i_descarte";
     if ($iLote != "") {
     	 $sql .= " and vc19_i_matetoqueitemlote = $iLote ";
     }
     $sql .= "   left join matestoqueitemlote as a  on a.m77_sequencial  = vac_descarte.vc19_i_matetoqueitemlote";
     $sql .= "   left join matestoqueitem as a1     on a1.m71_codlanc    = a.m77_matestoqueitem";
     $sql .= "   left join matestoque as a2         on a2.m70_codigo       = a1.m71_codmatestoque ";
     $sql .= "   left join vac_vacinamaterial as a3 on a3.vc29_i_vacina    = vac_descarte.vc19_i_vacina ";
     $sql .= "                                     and a3.vc29_i_material  = a2.m70_codmatmater ";
     $sql .= "   left join vac_fechaaplica          on vc21_i_fechamento = vac_fechamento.vc20_i_codigo";
     $sql .= "   left join vac_aplicalote           on vc17_i_codigo     = vac_fechaaplica.vc21_i_aplicalote";
     if ($iLote != "") {
       $sql .= " and vc17_i_matetoqueitemlote = $iLote ";
     }
     $sql .= "   left join matestoqueitemlote as b  on b.m77_sequencial  = vac_aplicalote.vc17_i_matetoqueitemlote";
     $sql .= "   left join matestoqueitem as b1     on b1.m71_codlanc    = b.m77_matestoqueitem ";
     $sql .= "   left join vac_aplica               on vc16_i_codigo        = vac_aplicalote.vc17_i_aplica ";   
     $sql .= "   left join vac_vacinadose           on vc07_i_codigo       = vac_aplica.vc16_i_dosevacina ";
     $sql .= "   left join matestoque as b2         on b2.m70_codigo       = b1.m71_codmatestoque ";
     $sql .= "   left join vac_vacinamaterial as b3 on b3.vc29_i_vacina   = vac_vacinadose.vc07_i_vacina ";
     $sql .= "                                     and b3.vc29_i_material = b2.m70_codmatmater ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc20_i_codigo!=null ){
         $sql2 .= " where vac_fechamento.vc20_i_codigo = $vc20_i_codigo "; 
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
  
// funcao do sql2
   function sql_query_atendrequiitem ( $vc20_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_fechamento ";
     $sql .= "  inner join db_usuarios         on id_usuario            = vac_fechamento.vc20_i_usuario";
     $sql .= "  inner join vac_fecharequi      on vc23_i_fechamento     = vac_fechamento.vc20_i_codigo";
     $sql .= "  inner join matrequi            on m40_codigo            = vac_fecharequi.vc23_i_matrequi";
     $sql .= "  inner join matrequiitem        on m41_codmatrequi       = matrequi.m40_codigo";
     $sql .= "  inner join atendrequiitem      on m43_codmatrequiitem   = matrequiitem.m41_codigo";
     $sql .= "  inner join atendrequi          on m42_codigo            = atendrequiitem.m43_codatendrequi";
     $sql .= "  inner join matestoqueinimeiari on m49_codatendrequiitem = atendrequiitem.m43_codigo";
     $sql .= "  inner join atendrequiitemmei   on m44_codatendreqitem   = atendrequiitem.m43_codigo";
     $sql .= "  inner join matestoqueitem      on m71_codlanc           = atendrequiitemmei.m44_codmatestoqueitem";
     $sql .= "  inner join matestoqueitemlote  on m77_matestoqueitem    = matestoqueitem.m71_codlanc";
     $sql2 = "";
     if($dbwhere==""){
       if($vc20_i_codigo!=null ){
         $sql2 .= " where vac_fechamento.vc20_i_codigo = $vc20_i_codigo "; 
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
   function sql_query_file ( $vc20_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_fechamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc20_i_codigo!=null ){
         $sql2 .= " where vac_fechamento.vc20_i_codigo = $vc20_i_codigo "; 
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