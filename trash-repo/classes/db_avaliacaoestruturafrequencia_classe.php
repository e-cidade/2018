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

//MODULO: escola
//CLASSE DA ENTIDADE avaliacaoestruturafrequencia
class cl_avaliacaoestruturafrequencia { 
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
   var $ed328_sequencial = 0; 
   var $ed328_escola = 0; 
   var $ed328_db_estrutura = 0; 
   var $ed328_ativo = 'f'; 
   var $ed328_arredondafrequencia = 'f'; 
   var $ed328_observacao = null; 
   var $ed328_ano = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed328_sequencial = int4 = Código Estrutura Frequência 
                 ed328_escola = int4 = Código da Escola 
                 ed328_db_estrutura = int4 = Código do Estrutural 
                 ed328_ativo = bool = Ativo 
                 ed328_arredondafrequencia = bool = Arredonda Frequência 
                 ed328_observacao = text = Observação 
                 ed328_ano = int4 = Ano 
                 ";
   //funcao construtor da classe 
   function cl_avaliacaoestruturafrequencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaoestruturafrequencia"); 
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
       $this->ed328_sequencial = ($this->ed328_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed328_sequencial"]:$this->ed328_sequencial);
       $this->ed328_escola = ($this->ed328_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed328_escola"]:$this->ed328_escola);
       $this->ed328_db_estrutura = ($this->ed328_db_estrutura == ""?@$GLOBALS["HTTP_POST_VARS"]["ed328_db_estrutura"]:$this->ed328_db_estrutura);
       $this->ed328_ativo = ($this->ed328_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed328_ativo"]:$this->ed328_ativo);
       $this->ed328_arredondafrequencia = ($this->ed328_arredondafrequencia == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed328_arredondafrequencia"]:$this->ed328_arredondafrequencia);
       $this->ed328_observacao = ($this->ed328_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed328_observacao"]:$this->ed328_observacao);
       $this->ed328_ano = ($this->ed328_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed328_ano"]:$this->ed328_ano);
     }else{
       $this->ed328_sequencial = ($this->ed328_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed328_sequencial"]:$this->ed328_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed328_sequencial){ 
      $this->atualizacampos();
     if($this->ed328_escola == null ){ 
       $this->erro_sql = " Campo Código da Escola nao Informado.";
       $this->erro_campo = "ed328_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed328_db_estrutura == null ){ 
       $this->erro_sql = " Campo Código do Estrutural nao Informado.";
       $this->erro_campo = "ed328_db_estrutura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed328_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "ed328_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed328_arredondafrequencia == null ){ 
       $this->erro_sql = " Campo Arredonda Frequência nao Informado.";
       $this->erro_campo = "ed328_arredondafrequencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed328_ano == null ){ 
       $this->ed328_ano = "0";
     }
     if($ed328_sequencial == "" || $ed328_sequencial == null ){
       $result = db_query("select nextval('avaliacaoestruturafrequencia_ed328_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaoestruturafrequencia_ed328_sequencial_seq do campo: ed328_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed328_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaoestruturafrequencia_ed328_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed328_sequencial)){
         $this->erro_sql = " Campo ed328_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed328_sequencial = $ed328_sequencial; 
       }
     }
     if(($this->ed328_sequencial == null) || ($this->ed328_sequencial == "") ){ 
       $this->erro_sql = " Campo ed328_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaoestruturafrequencia(
                                       ed328_sequencial 
                                      ,ed328_escola 
                                      ,ed328_db_estrutura 
                                      ,ed328_ativo 
                                      ,ed328_arredondafrequencia 
                                      ,ed328_observacao 
                                      ,ed328_ano 
                       )
                values (
                                $this->ed328_sequencial 
                               ,$this->ed328_escola 
                               ,$this->ed328_db_estrutura 
                               ,'$this->ed328_ativo' 
                               ,'$this->ed328_arredondafrequencia' 
                               ,'$this->ed328_observacao' 
                               ,$this->ed328_ano 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliação Estrutura Frequência ($this->ed328_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliação Estrutura Frequência já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliação Estrutura Frequência ($this->ed328_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed328_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed328_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19981,'$this->ed328_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3582,19981,'','".AddSlashes(pg_result($resaco,0,'ed328_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3582,19982,'','".AddSlashes(pg_result($resaco,0,'ed328_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3582,19983,'','".AddSlashes(pg_result($resaco,0,'ed328_db_estrutura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3582,19984,'','".AddSlashes(pg_result($resaco,0,'ed328_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3582,19985,'','".AddSlashes(pg_result($resaco,0,'ed328_arredondafrequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3582,19986,'','".AddSlashes(pg_result($resaco,0,'ed328_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3582,19987,'','".AddSlashes(pg_result($resaco,0,'ed328_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed328_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update avaliacaoestruturafrequencia set ";
     $virgula = "";
     if(trim($this->ed328_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed328_sequencial"])){ 
       $sql  .= $virgula." ed328_sequencial = $this->ed328_sequencial ";
       $virgula = ",";
       if(trim($this->ed328_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Estrutura Frequência nao Informado.";
         $this->erro_campo = "ed328_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed328_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed328_escola"])){ 
       $sql  .= $virgula." ed328_escola = $this->ed328_escola ";
       $virgula = ",";
       if(trim($this->ed328_escola) == null ){ 
         $this->erro_sql = " Campo Código da Escola nao Informado.";
         $this->erro_campo = "ed328_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed328_db_estrutura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed328_db_estrutura"])){ 
       $sql  .= $virgula." ed328_db_estrutura = $this->ed328_db_estrutura ";
       $virgula = ",";
       if(trim($this->ed328_db_estrutura) == null ){ 
         $this->erro_sql = " Campo Código do Estrutural nao Informado.";
         $this->erro_campo = "ed328_db_estrutura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed328_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed328_ativo"])){ 
       $sql  .= $virgula." ed328_ativo = '$this->ed328_ativo' ";
       $virgula = ",";
       if(trim($this->ed328_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "ed328_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed328_arredondafrequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed328_arredondafrequencia"])){ 
       $sql  .= $virgula." ed328_arredondafrequencia = '$this->ed328_arredondafrequencia' ";
       $virgula = ",";
       if(trim($this->ed328_arredondafrequencia) == null ){ 
         $this->erro_sql = " Campo Arredonda Frequência nao Informado.";
         $this->erro_campo = "ed328_arredondafrequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed328_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed328_observacao"])){ 
       $sql  .= $virgula." ed328_observacao = '$this->ed328_observacao' ";
       $virgula = ",";
     }
     if(trim($this->ed328_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed328_ano"])){ 
        if(trim($this->ed328_ano)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed328_ano"])){ 
           $this->ed328_ano = "0" ; 
        } 
       $sql  .= $virgula." ed328_ano = $this->ed328_ano ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed328_sequencial!=null){
       $sql .= " ed328_sequencial = $this->ed328_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed328_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19981,'$this->ed328_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed328_sequencial"]) || $this->ed328_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3582,19981,'".AddSlashes(pg_result($resaco,$conresaco,'ed328_sequencial'))."','$this->ed328_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed328_escola"]) || $this->ed328_escola != "")
             $resac = db_query("insert into db_acount values($acount,3582,19982,'".AddSlashes(pg_result($resaco,$conresaco,'ed328_escola'))."','$this->ed328_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed328_db_estrutura"]) || $this->ed328_db_estrutura != "")
             $resac = db_query("insert into db_acount values($acount,3582,19983,'".AddSlashes(pg_result($resaco,$conresaco,'ed328_db_estrutura'))."','$this->ed328_db_estrutura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed328_ativo"]) || $this->ed328_ativo != "")
             $resac = db_query("insert into db_acount values($acount,3582,19984,'".AddSlashes(pg_result($resaco,$conresaco,'ed328_ativo'))."','$this->ed328_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed328_arredondafrequencia"]) || $this->ed328_arredondafrequencia != "")
             $resac = db_query("insert into db_acount values($acount,3582,19985,'".AddSlashes(pg_result($resaco,$conresaco,'ed328_arredondafrequencia'))."','$this->ed328_arredondafrequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed328_observacao"]) || $this->ed328_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3582,19986,'".AddSlashes(pg_result($resaco,$conresaco,'ed328_observacao'))."','$this->ed328_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed328_ano"]) || $this->ed328_ano != "")
             $resac = db_query("insert into db_acount values($acount,3582,19987,'".AddSlashes(pg_result($resaco,$conresaco,'ed328_ano'))."','$this->ed328_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação Estrutura Frequência nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed328_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação Estrutura Frequência nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed328_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed328_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed328_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed328_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19981,'$ed328_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3582,19981,'','".AddSlashes(pg_result($resaco,$iresaco,'ed328_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3582,19982,'','".AddSlashes(pg_result($resaco,$iresaco,'ed328_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3582,19983,'','".AddSlashes(pg_result($resaco,$iresaco,'ed328_db_estrutura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3582,19984,'','".AddSlashes(pg_result($resaco,$iresaco,'ed328_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3582,19985,'','".AddSlashes(pg_result($resaco,$iresaco,'ed328_arredondafrequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3582,19986,'','".AddSlashes(pg_result($resaco,$iresaco,'ed328_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3582,19987,'','".AddSlashes(pg_result($resaco,$iresaco,'ed328_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaoestruturafrequencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed328_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed328_sequencial = $ed328_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação Estrutura Frequência nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed328_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação Estrutura Frequência nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed328_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed328_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaoestruturafrequencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed328_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaoestruturafrequencia ";
     $sql .= "      inner join db_estrutura     on db_estrutura.db77_codestrut     = avaliacaoestruturafrequencia.ed328_db_estrutura";
     $sql .= "      inner join escola           on escola.ed18_i_codigo            = avaliacaoestruturafrequencia.ed328_escola";
     $sql .= "      inner join bairro           on bairro.j13_codi                 = escola.ed18_i_bairro";
     $sql .= "      inner join ruas             on ruas.j14_codigo                 = escola.ed18_i_rua";
     $sql .= "      inner join db_depart        on db_depart.coddepto              = escola.ed18_i_codigo";
     $sql .= "      inner join censouf          on censouf.ed260_i_codigo          = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic       on censomunic.ed261_i_codigo       = escola.ed18_i_censomunic";
     $sql .= "      inner join censodistrito    on censodistrito.ed262_i_codigo    = escola.ed18_i_censodistrito";
     $sql .= "      left  join censoorgreg      on censoorgreg.ed263_i_codigo      = escola.ed18_i_censoorgreg";
     $sql .= "      left  join censolinguaindig on censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql2 = "";
     if($dbwhere==""){
       if($ed328_sequencial!=null ){
         $sql2 .= " where avaliacaoestruturafrequencia.ed328_sequencial = $ed328_sequencial "; 
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
   function sql_query_file ( $ed328_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaoestruturafrequencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed328_sequencial!=null ){
         $sql2 .= " where avaliacaoestruturafrequencia.ed328_sequencial = $ed328_sequencial "; 
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
  
  function sql_query_configuracao_escola($ed328_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
  
    $sql = "select ";
    if ($campos != "*" ) {
  
      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from avaliacaoestruturafrequencia ";
    $sql .= "      inner join db_estrutura                      on db77_codestrut                     = ed328_db_estrutura";
    $sql .= "      left  join avaliacaoestruturaregrafrequencia on ed329_avaliacaoestruturafrequencia = ed328_sequencial";
    $sql .= "      left  join regraarredondamento               on ed316_sequencial                   = ed329_regraarredondamento";
    $sql2 = "";
    
    if ($dbwhere == "") {
      
      if ($ed328_sequencial != null ) {
        $sql2 .= " where avaliacaoestruturafrequencia.ed328_sequencial = $ed328_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    
    $sql .= $sql2;
    if ($ordem != null ) {
      
      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }
}
?>