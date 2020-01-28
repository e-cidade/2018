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
//CLASSE DA ENTIDADE db_projetoscliente
class cl_db_projetoscliente { 
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
   var $at60_codproj = 0; 
   var $at60_codcli = 0; 
   var $at60_codproced = 0; 
   var $at60_inicio_dia = null; 
   var $at60_inicio_mes = null; 
   var $at60_inicio_ano = null; 
   var $at60_inicio = null; 
   var $at60_fim_dia = null; 
   var $at60_fim_mes = null; 
   var $at60_fim_ano = null; 
   var $at60_fim = null; 
   var $at60_descricao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at60_codproj = int4 = Codigo do projeto 
                 at60_codcli = int4 = Clientes 
                 at60_codproced = int4 = Cod. Proced 
                 at60_inicio = date = Inicio 
                 at60_fim = date = Fim 
                 at60_descricao = text = Descrição 
                 ";
   //funcao construtor da classe 
   function cl_db_projetoscliente() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_projetoscliente"); 
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
       $this->at60_codproj = ($this->at60_codproj == ""?@$GLOBALS["HTTP_POST_VARS"]["at60_codproj"]:$this->at60_codproj);
       $this->at60_codcli = ($this->at60_codcli == ""?@$GLOBALS["HTTP_POST_VARS"]["at60_codcli"]:$this->at60_codcli);
       $this->at60_codproced = ($this->at60_codproced == ""?@$GLOBALS["HTTP_POST_VARS"]["at60_codproced"]:$this->at60_codproced);
       if($this->at60_inicio == ""){
         $this->at60_inicio_dia = ($this->at60_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at60_inicio_dia"]:$this->at60_inicio_dia);
         $this->at60_inicio_mes = ($this->at60_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at60_inicio_mes"]:$this->at60_inicio_mes);
         $this->at60_inicio_ano = ($this->at60_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at60_inicio_ano"]:$this->at60_inicio_ano);
         if($this->at60_inicio_dia != ""){
            $this->at60_inicio = $this->at60_inicio_ano."-".$this->at60_inicio_mes."-".$this->at60_inicio_dia;
         }
       }
       if($this->at60_fim == ""){
         $this->at60_fim_dia = ($this->at60_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at60_fim_dia"]:$this->at60_fim_dia);
         $this->at60_fim_mes = ($this->at60_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at60_fim_mes"]:$this->at60_fim_mes);
         $this->at60_fim_ano = ($this->at60_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at60_fim_ano"]:$this->at60_fim_ano);
         if($this->at60_fim_dia != ""){
            $this->at60_fim = $this->at60_fim_ano."-".$this->at60_fim_mes."-".$this->at60_fim_dia;
         }
       }
       $this->at60_descricao = ($this->at60_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["at60_descricao"]:$this->at60_descricao);
     }else{
       $this->at60_codproj = ($this->at60_codproj == ""?@$GLOBALS["HTTP_POST_VARS"]["at60_codproj"]:$this->at60_codproj);
     }
   }
   // funcao para inclusao
   function incluir ($at60_codproj){ 
      $this->atualizacampos();
     if($this->at60_codcli == null ){ 
       $this->erro_sql = " Campo Clientes nao Informado.";
       $this->erro_campo = "at60_codcli";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at60_codproced == null ){ 
       $this->erro_sql = " Campo Cod. Proced nao Informado.";
       $this->erro_campo = "at60_codproced";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at60_inicio == null ){ 
       $this->erro_sql = " Campo Inicio nao Informado.";
       $this->erro_campo = "at60_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at60_fim == null ){ 
       $this->erro_sql = " Campo Fim nao Informado.";
       $this->erro_campo = "at60_fim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at60_codproj == "" || $at60_codproj == null ){
       $result = db_query("select nextval('db_projetos_at60_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_projetos_at60_codigo_seq do campo: at60_codproj"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at60_codproj = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_projetos_at60_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $at60_codproj)){
         $this->erro_sql = " Campo at60_codproj maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at60_codproj = $at60_codproj; 
       }
     }
     if(($this->at60_codproj == null) || ($this->at60_codproj == "") ){ 
       $this->erro_sql = " Campo at60_codproj nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_projetoscliente(
                                       at60_codproj 
                                      ,at60_codcli 
                                      ,at60_codproced 
                                      ,at60_inicio 
                                      ,at60_fim 
                                      ,at60_descricao 
                       )
                values (
                                $this->at60_codproj 
                               ,$this->at60_codcli 
                               ,$this->at60_codproced 
                               ,".($this->at60_inicio == "null" || $this->at60_inicio == ""?"null":"'".$this->at60_inicio."'")." 
                               ,".($this->at60_fim == "null" || $this->at60_fim == ""?"null":"'".$this->at60_fim."'")." 
                               ,'$this->at60_descricao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Projetos ($this->at60_codproj) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Projetos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Projetos ($this->at60_codproj) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at60_codproj;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at60_codproj));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8345,'$this->at60_codproj','I')");
       $resac = db_query("insert into db_acount values($acount,1410,8345,'','".AddSlashes(pg_result($resaco,0,'at60_codproj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1410,8346,'','".AddSlashes(pg_result($resaco,0,'at60_codcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1410,8347,'','".AddSlashes(pg_result($resaco,0,'at60_codproced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1410,8348,'','".AddSlashes(pg_result($resaco,0,'at60_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1410,8349,'','".AddSlashes(pg_result($resaco,0,'at60_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1410,11854,'','".AddSlashes(pg_result($resaco,0,'at60_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at60_codproj=null) { 
      $this->atualizacampos();
     $sql = " update db_projetoscliente set ";
     $virgula = "";
     if(trim($this->at60_codproj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at60_codproj"])){ 
       $sql  .= $virgula." at60_codproj = $this->at60_codproj ";
       $virgula = ",";
       if(trim($this->at60_codproj) == null ){ 
         $this->erro_sql = " Campo Codigo do projeto nao Informado.";
         $this->erro_campo = "at60_codproj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at60_codcli)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at60_codcli"])){ 
       $sql  .= $virgula." at60_codcli = $this->at60_codcli ";
       $virgula = ",";
       if(trim($this->at60_codcli) == null ){ 
         $this->erro_sql = " Campo Clientes nao Informado.";
         $this->erro_campo = "at60_codcli";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at60_codproced)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at60_codproced"])){ 
       $sql  .= $virgula." at60_codproced = $this->at60_codproced ";
       $virgula = ",";
       if(trim($this->at60_codproced) == null ){ 
         $this->erro_sql = " Campo Cod. Proced nao Informado.";
         $this->erro_campo = "at60_codproced";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at60_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at60_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at60_inicio_dia"] !="") ){ 
       $sql  .= $virgula." at60_inicio = '$this->at60_inicio' ";
       $virgula = ",";
       if(trim($this->at60_inicio) == null ){ 
         $this->erro_sql = " Campo Inicio nao Informado.";
         $this->erro_campo = "at60_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at60_inicio_dia"])){ 
         $sql  .= $virgula." at60_inicio = null ";
         $virgula = ",";
         if(trim($this->at60_inicio) == null ){ 
           $this->erro_sql = " Campo Inicio nao Informado.";
           $this->erro_campo = "at60_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at60_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at60_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at60_fim_dia"] !="") ){ 
       $sql  .= $virgula." at60_fim = '$this->at60_fim' ";
       $virgula = ",";
       if(trim($this->at60_fim) == null ){ 
         $this->erro_sql = " Campo Fim nao Informado.";
         $this->erro_campo = "at60_fim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at60_fim_dia"])){ 
         $sql  .= $virgula." at60_fim = null ";
         $virgula = ",";
         if(trim($this->at60_fim) == null ){ 
           $this->erro_sql = " Campo Fim nao Informado.";
           $this->erro_campo = "at60_fim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at60_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at60_descricao"])){ 
       $sql  .= $virgula." at60_descricao = '$this->at60_descricao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($at60_codproj!=null){
       $sql .= " at60_codproj = $this->at60_codproj";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at60_codproj));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8345,'$this->at60_codproj','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at60_codproj"]))
           $resac = db_query("insert into db_acount values($acount,1410,8345,'".AddSlashes(pg_result($resaco,$conresaco,'at60_codproj'))."','$this->at60_codproj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at60_codcli"]))
           $resac = db_query("insert into db_acount values($acount,1410,8346,'".AddSlashes(pg_result($resaco,$conresaco,'at60_codcli'))."','$this->at60_codcli',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at60_codproced"]))
           $resac = db_query("insert into db_acount values($acount,1410,8347,'".AddSlashes(pg_result($resaco,$conresaco,'at60_codproced'))."','$this->at60_codproced',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at60_inicio"]))
           $resac = db_query("insert into db_acount values($acount,1410,8348,'".AddSlashes(pg_result($resaco,$conresaco,'at60_inicio'))."','$this->at60_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at60_fim"]))
           $resac = db_query("insert into db_acount values($acount,1410,8349,'".AddSlashes(pg_result($resaco,$conresaco,'at60_fim'))."','$this->at60_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at60_descricao"]))
           $resac = db_query("insert into db_acount values($acount,1410,11854,'".AddSlashes(pg_result($resaco,$conresaco,'at60_descricao'))."','$this->at60_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Projetos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at60_codproj;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Projetos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at60_codproj;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at60_codproj;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at60_codproj=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at60_codproj));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8345,'$at60_codproj','E')");
         $resac = db_query("insert into db_acount values($acount,1410,8345,'','".AddSlashes(pg_result($resaco,$iresaco,'at60_codproj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1410,8346,'','".AddSlashes(pg_result($resaco,$iresaco,'at60_codcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1410,8347,'','".AddSlashes(pg_result($resaco,$iresaco,'at60_codproced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1410,8348,'','".AddSlashes(pg_result($resaco,$iresaco,'at60_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1410,8349,'','".AddSlashes(pg_result($resaco,$iresaco,'at60_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1410,11854,'','".AddSlashes(pg_result($resaco,$iresaco,'at60_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_projetoscliente
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at60_codproj != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at60_codproj = $at60_codproj ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Projetos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at60_codproj;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Projetos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at60_codproj;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at60_codproj;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_projetoscliente";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at60_codproj=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_projetoscliente ";
     $sql .= "      inner join clientes  on  clientes.at01_codcli = db_projetoscliente.at60_codcli";
     $sql .= "      inner join db_syscadproced  on  db_syscadproced.codproced = db_projetoscliente.at60_codproced";
     $sql .= "      inner join db_sysmodulo  on  db_sysmodulo.codmod = db_syscadproced.codmod";
     $sql .= "      inner join atendcadarea  on  atendcadarea.at26_sequencial = db_syscadproced.codarea";
     $sql2 = "";
     if($dbwhere==""){
       if($at60_codproj!=null ){
         $sql2 .= " where db_projetoscliente.at60_codproj = $at60_codproj "; 
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
   function sql_query_file ( $at60_codproj=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_projetoscliente ";
     $sql2 = "";
     if($dbwhere==""){
       if($at60_codproj!=null ){
         $sql2 .= " where db_projetoscliente.at60_codproj = $at60_codproj "; 
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