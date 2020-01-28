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

//MODULO: atendimento
//CLASSE DA ENTIDADE db_proced
class cl_db_proced { 
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
   var $at30_codigo = 0; 
   var $at30_descr = null; 
   var $at30_responsavel = 0; 
   var $at30_inicio_dia = null; 
   var $at30_inicio_mes = null; 
   var $at30_inicio_ano = null; 
   var $at30_inicio = null; 
   var $at30_fim_dia = null; 
   var $at30_fim_mes = null; 
   var $at30_fim_ano = null; 
   var $at30_fim = null; 
   var $at30_situacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at30_codigo = int4 = Codigo do procedimento 
                 at30_descr = text = Descricao do procedimento 
                 at30_responsavel = int4 = Cod. Usuário 
                 at30_inicio = date = Inicio 
                 at30_fim = date = Fim 
                 at30_situacao = int4 = Codigo 
                 ";
   //funcao construtor da classe 
   function cl_db_proced() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_proced"); 
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
       $this->at30_codigo = ($this->at30_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["at30_codigo"]:$this->at30_codigo);
       $this->at30_descr = ($this->at30_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["at30_descr"]:$this->at30_descr);
       $this->at30_responsavel = ($this->at30_responsavel == ""?@$GLOBALS["HTTP_POST_VARS"]["at30_responsavel"]:$this->at30_responsavel);
       if($this->at30_inicio == ""){
         $this->at30_inicio_dia = ($this->at30_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at30_inicio_dia"]:$this->at30_inicio_dia);
         $this->at30_inicio_mes = ($this->at30_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at30_inicio_mes"]:$this->at30_inicio_mes);
         $this->at30_inicio_ano = ($this->at30_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at30_inicio_ano"]:$this->at30_inicio_ano);
         if($this->at30_inicio_dia != ""){
            $this->at30_inicio = $this->at30_inicio_ano."-".$this->at30_inicio_mes."-".$this->at30_inicio_dia;
         }
       }
       if($this->at30_fim == ""){
         $this->at30_fim_dia = ($this->at30_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at30_fim_dia"]:$this->at30_fim_dia);
         $this->at30_fim_mes = ($this->at30_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at30_fim_mes"]:$this->at30_fim_mes);
         $this->at30_fim_ano = ($this->at30_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at30_fim_ano"]:$this->at30_fim_ano);
         if($this->at30_fim_dia != ""){
            $this->at30_fim = $this->at30_fim_ano."-".$this->at30_fim_mes."-".$this->at30_fim_dia;
         }
       }
       $this->at30_situacao = ($this->at30_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["at30_situacao"]:$this->at30_situacao);
     }else{
       $this->at30_codigo = ($this->at30_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["at30_codigo"]:$this->at30_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($at30_codigo){ 
      $this->atualizacampos();
     if($this->at30_descr == null ){ 
       $this->erro_sql = " Campo Descricao do procedimento nao Informado.";
       $this->erro_campo = "at30_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at30_responsavel == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "at30_responsavel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at30_inicio == null ){ 
       $this->erro_sql = " Campo Inicio nao Informado.";
       $this->erro_campo = "at30_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at30_fim == null ){ 
       $this->erro_sql = " Campo Fim nao Informado.";
       $this->erro_campo = "at30_fim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at30_situacao == null ){ 
       $this->erro_sql = " Campo Codigo nao Informado.";
       $this->erro_campo = "at30_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at30_codigo == "" || $at30_codigo == null ){
       $result = db_query("select nextval('db_projetos_at30_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_projetos_at30_codigo_seq do campo: at30_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at30_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_projetos_at30_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $at30_codigo)){
         $this->erro_sql = " Campo at30_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at30_codigo = $at30_codigo; 
       }
     }
     if(($this->at30_codigo == null) || ($this->at30_codigo == "") ){ 
       $this->erro_sql = " Campo at30_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_proced(
                                       at30_codigo 
                                      ,at30_descr 
                                      ,at30_responsavel 
                                      ,at30_inicio 
                                      ,at30_fim 
                                      ,at30_situacao 
                       )
                values (
                                $this->at30_codigo 
                               ,'$this->at30_descr' 
                               ,$this->at30_responsavel 
                               ,".($this->at30_inicio == "null" || $this->at30_inicio == ""?"null":"'".$this->at30_inicio."'")." 
                               ,".($this->at30_fim == "null" || $this->at30_fim == ""?"null":"'".$this->at30_fim."'")." 
                               ,$this->at30_situacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Procedimentos ($this->at30_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Procedimentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Procedimentos ($this->at30_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at30_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at30_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8087,'$this->at30_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1362,8087,'','".AddSlashes(pg_result($resaco,0,'at30_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1362,8089,'','".AddSlashes(pg_result($resaco,0,'at30_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1362,8090,'','".AddSlashes(pg_result($resaco,0,'at30_responsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1362,8092,'','".AddSlashes(pg_result($resaco,0,'at30_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1362,8093,'','".AddSlashes(pg_result($resaco,0,'at30_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1362,8096,'','".AddSlashes(pg_result($resaco,0,'at30_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at30_codigo=null) { 
      $this->atualizacampos();
     $sql = " update db_proced set ";
     $virgula = "";
     if(trim($this->at30_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at30_codigo"])){ 
       $sql  .= $virgula." at30_codigo = $this->at30_codigo ";
       $virgula = ",";
       if(trim($this->at30_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo do procedimento nao Informado.";
         $this->erro_campo = "at30_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at30_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at30_descr"])){ 
       $sql  .= $virgula." at30_descr = '$this->at30_descr' ";
       $virgula = ",";
       if(trim($this->at30_descr) == null ){ 
         $this->erro_sql = " Campo Descricao do procedimento nao Informado.";
         $this->erro_campo = "at30_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at30_responsavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at30_responsavel"])){ 
       $sql  .= $virgula." at30_responsavel = $this->at30_responsavel ";
       $virgula = ",";
       if(trim($this->at30_responsavel) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "at30_responsavel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at30_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at30_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at30_inicio_dia"] !="") ){ 
       $sql  .= $virgula." at30_inicio = '$this->at30_inicio' ";
       $virgula = ",";
       if(trim($this->at30_inicio) == null ){ 
         $this->erro_sql = " Campo Inicio nao Informado.";
         $this->erro_campo = "at30_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at30_inicio_dia"])){ 
         $sql  .= $virgula." at30_inicio = null ";
         $virgula = ",";
         if(trim($this->at30_inicio) == null ){ 
           $this->erro_sql = " Campo Inicio nao Informado.";
           $this->erro_campo = "at30_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at30_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at30_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at30_fim_dia"] !="") ){ 
       $sql  .= $virgula." at30_fim = '$this->at30_fim' ";
       $virgula = ",";
       if(trim($this->at30_fim) == null ){ 
         $this->erro_sql = " Campo Fim nao Informado.";
         $this->erro_campo = "at30_fim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at30_fim_dia"])){ 
         $sql  .= $virgula." at30_fim = null ";
         $virgula = ",";
         if(trim($this->at30_fim) == null ){ 
           $this->erro_sql = " Campo Fim nao Informado.";
           $this->erro_campo = "at30_fim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at30_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at30_situacao"])){ 
       $sql  .= $virgula." at30_situacao = $this->at30_situacao ";
       $virgula = ",";
       if(trim($this->at30_situacao) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "at30_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at30_codigo!=null){
       $sql .= " at30_codigo = $this->at30_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at30_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8087,'$this->at30_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at30_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1362,8087,'".AddSlashes(pg_result($resaco,$conresaco,'at30_codigo'))."','$this->at30_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at30_descr"]))
           $resac = db_query("insert into db_acount values($acount,1362,8089,'".AddSlashes(pg_result($resaco,$conresaco,'at30_descr'))."','$this->at30_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at30_responsavel"]))
           $resac = db_query("insert into db_acount values($acount,1362,8090,'".AddSlashes(pg_result($resaco,$conresaco,'at30_responsavel'))."','$this->at30_responsavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at30_inicio"]))
           $resac = db_query("insert into db_acount values($acount,1362,8092,'".AddSlashes(pg_result($resaco,$conresaco,'at30_inicio'))."','$this->at30_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at30_fim"]))
           $resac = db_query("insert into db_acount values($acount,1362,8093,'".AddSlashes(pg_result($resaco,$conresaco,'at30_fim'))."','$this->at30_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at30_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1362,8096,'".AddSlashes(pg_result($resaco,$conresaco,'at30_situacao'))."','$this->at30_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at30_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at30_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at30_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at30_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at30_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8087,'$at30_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1362,8087,'','".AddSlashes(pg_result($resaco,$iresaco,'at30_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1362,8089,'','".AddSlashes(pg_result($resaco,$iresaco,'at30_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1362,8090,'','".AddSlashes(pg_result($resaco,$iresaco,'at30_responsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1362,8092,'','".AddSlashes(pg_result($resaco,$iresaco,'at30_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1362,8093,'','".AddSlashes(pg_result($resaco,$iresaco,'at30_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1362,8096,'','".AddSlashes(pg_result($resaco,$iresaco,'at30_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_proced
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at30_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at30_codigo = $at30_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at30_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at30_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at30_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_proced";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at30_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_proced ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_proced.at30_responsavel";
     $sql .= "      inner join db_procedsituacao  on  db_procedsituacao.at32_codigo = db_proced.at30_situacao";
     $sql2 = "";
     if($dbwhere==""){
       if($at30_codigo!=null ){
         $sql2 .= " where db_proced.at30_codigo = $at30_codigo "; 
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
   function sql_query_aut ( $at30_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_proced ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_proced.at30_responsavel";
     $sql .= "      inner join db_procedsituacao  on  db_procedsituacao.at32_codigo = db_proced.at30_situacao";
     $sql .= "      inner join db_procedaut on db_proced.at30_codigo = db_procedaut.at56_proced";
     $sql2 = "";
     if($dbwhere==""){
       if($at30_codigo!=null ){
         $sql2 .= " where db_proced.at30_codigo = $at30_codigo "; 
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
   function sql_query_file ( $at30_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_proced ";
     $sql2 = "";
     if($dbwhere==""){
       if($at30_codigo!=null ){
         $sql2 .= " where db_proced.at30_codigo = $at30_codigo "; 
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
   function sql_query_usu ( $at30_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_proced ";
     $sql .= "      inner join db_procedgrupos on db_procedgrupos.at52_proced = db_proced.at30_codigo ";
		 $sql .= "      inner join db_procedcadgrupos on db_procedcadgrupos.at51_codigo = db_procedgrupos.at52_grupo ";
		 $sql .= "      inner join db_procedusu on db_procedusu.at31_proced = db_proced.at30_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($at30_codigo!=null ){
         $sql2 .= " where db_proced.at30_codigo = $at30_codigo "; 
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