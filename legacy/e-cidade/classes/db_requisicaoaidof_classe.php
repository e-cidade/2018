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

//MODULO: fiscal
//CLASSE DA ENTIDADE requisicaoaidof
class cl_requisicaoaidof { 
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
   var $y116_id = 0; 
   var $y116_tipodocumento = 0; 
   var $y116_codigografica = 0; 
   var $y116_idusuario = 0; 
   var $y116_inscricaomunicipal = 0; 
   var $y116_datalancamento_dia = null; 
   var $y116_datalancamento_mes = null; 
   var $y116_datalancamento_ano = null; 
   var $y116_datalancamento = null; 
   var $y116_quantidadesolicitada = 0; 
   var $y116_quantidadeLiberada = 0; 
   var $y116_status = null; 
   var $y116_observacao = null; 
   var $y116_codigoaidof = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y116_id = int4 = Código 
                 y116_tipodocumento = int4 = Tipo de Documento 
                 y116_codigografica = int4 = Código da Gráfica 
                 y116_idusuario = int4 = Código Usuário 
                 y116_inscricaomunicipal = int4 = Inscrição Municipal 
                 y116_datalancamento = date = Data do Lançamento 
                 y116_quantidadesolicitada = int4 = Quantidade Solicitada 
                 y116_quantidadeLiberada = int4 = Quantidade Liberada 
                 y116_status = char(1) = Status 
                 y116_observacao = text = Observação 
                 y116_codigoaidof = int4 = Código Aidof 
                 ";
   //funcao construtor da classe 
   function cl_requisicaoaidof() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("requisicaoaidof"); 
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
       $this->y116_id = ($this->y116_id == ""?@$GLOBALS["HTTP_POST_VARS"]["y116_id"]:$this->y116_id);
       $this->y116_tipodocumento = ($this->y116_tipodocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["y116_tipodocumento"]:$this->y116_tipodocumento);
       $this->y116_codigografica = ($this->y116_codigografica == ""?@$GLOBALS["HTTP_POST_VARS"]["y116_codigografica"]:$this->y116_codigografica);
       $this->y116_idusuario = ($this->y116_idusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["y116_idusuario"]:$this->y116_idusuario);
       $this->y116_inscricaomunicipal = ($this->y116_inscricaomunicipal == ""?@$GLOBALS["HTTP_POST_VARS"]["y116_inscricaomunicipal"]:$this->y116_inscricaomunicipal);
       if($this->y116_datalancamento == ""){
         $this->y116_datalancamento_dia = ($this->y116_datalancamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y116_datalancamento_dia"]:$this->y116_datalancamento_dia);
         $this->y116_datalancamento_mes = ($this->y116_datalancamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y116_datalancamento_mes"]:$this->y116_datalancamento_mes);
         $this->y116_datalancamento_ano = ($this->y116_datalancamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y116_datalancamento_ano"]:$this->y116_datalancamento_ano);
         if($this->y116_datalancamento_dia != ""){
            $this->y116_datalancamento = $this->y116_datalancamento_ano."-".$this->y116_datalancamento_mes."-".$this->y116_datalancamento_dia;
         }
       }
       $this->y116_quantidadesolicitada = ($this->y116_quantidadesolicitada == ""?@$GLOBALS["HTTP_POST_VARS"]["y116_quantidadesolicitada"]:$this->y116_quantidadesolicitada);
       $this->y116_quantidadeLiberada = ($this->y116_quantidadeLiberada == ""?@$GLOBALS["HTTP_POST_VARS"]["y116_quantidadeLiberada"]:$this->y116_quantidadeLiberada);
       $this->y116_status = ($this->y116_status == ""?@$GLOBALS["HTTP_POST_VARS"]["y116_status"]:$this->y116_status);
       $this->y116_observacao = ($this->y116_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["y116_observacao"]:$this->y116_observacao);
       $this->y116_codigoaidof = ($this->y116_codigoaidof == ""?@$GLOBALS["HTTP_POST_VARS"]["y116_codigoaidof"]:$this->y116_codigoaidof);
     }else{
       $this->y116_id = ($this->y116_id == ""?@$GLOBALS["HTTP_POST_VARS"]["y116_id"]:$this->y116_id);
     }
   }
   // funcao para inclusao
   function incluir ($y116_id){ 
      $this->atualizacampos();
     if($this->y116_tipodocumento == null ){ 
       $this->erro_sql = " Campo Tipo de Documento não informado.";
       $this->erro_campo = "y116_tipodocumento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y116_codigografica == null ){ 
       $this->erro_sql = " Campo Código da Gráfica não informado.";
       $this->erro_campo = "y116_codigografica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y116_idusuario == null ){ 
       $this->y116_idusuario = "NULL";
     }
     if($this->y116_inscricaomunicipal == null ){ 
       $this->erro_sql = " Campo Inscrição Municipal não informado.";
       $this->erro_campo = "y116_inscricaomunicipal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y116_datalancamento == null ){ 
       $this->erro_sql = " Campo Data do Lançamento não informado.";
       $this->erro_campo = "y116_datalancamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y116_quantidadesolicitada == null ){ 
       $this->erro_sql = " Campo Quantidade Solicitada não informado.";
       $this->erro_campo = "y116_quantidadesolicitada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y116_quantidadeLiberada == null ){ 
       $this->y116_quantidadeLiberada = "0";
     }
     if($this->y116_status == null ){ 
       $this->erro_sql = " Campo Status não informado.";
       $this->erro_campo = "y116_status";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y116_codigoaidof == null ){ 
       $this->y116_codigoaidof = "0";
     }
     if($y116_id == "" || $y116_id == null ){
       $result = db_query("select nextval('requisicaoaidof_y116_id_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: requisicaoaidof_y116_id_seq do campo: y116_id"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y116_id = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from requisicaoaidof_y116_id_seq");
       if(($result != false) && (pg_result($result,0,0) < $y116_id)){
         $this->erro_sql = " Campo y116_id maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y116_id = $y116_id; 
       }
     }
     if(($this->y116_id == null) || ($this->y116_id == "") ){ 
       $this->erro_sql = " Campo y116_id nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into requisicaoaidof(
                                       y116_id 
                                      ,y116_tipodocumento 
                                      ,y116_codigografica 
                                      ,y116_idusuario 
                                      ,y116_inscricaomunicipal 
                                      ,y116_datalancamento 
                                      ,y116_quantidadesolicitada 
                                      ,y116_quantidadeLiberada 
                                      ,y116_status 
                                      ,y116_observacao 
                                      ,y116_codigoaidof 
                       )
                values (
                                $this->y116_id 
                               ,$this->y116_tipodocumento 
                               ,$this->y116_codigografica 
                               ,$this->y116_idusuario 
                               ,$this->y116_inscricaomunicipal 
                               ,".($this->y116_datalancamento == "null" || $this->y116_datalancamento == ""?"null":"'".$this->y116_datalancamento."'")." 
                               ,$this->y116_quantidadesolicitada 
                               ,$this->y116_quantidadeLiberada 
                               ,'$this->y116_status' 
                               ,'$this->y116_observacao' 
                               ,$this->y116_codigoaidof 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Requisição de AIDOF ($this->y116_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Requisição de AIDOF já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Requisição de AIDOF ($this->y116_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y116_id;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->y116_id  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20023,'$this->y116_id','I')");
         $resac = db_query("insert into db_acount values($acount,3588,20023,'','".AddSlashes(pg_result($resaco,0,'y116_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3588,20024,'','".AddSlashes(pg_result($resaco,0,'y116_tipodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3588,20049,'','".AddSlashes(pg_result($resaco,0,'y116_codigografica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3588,20031,'','".AddSlashes(pg_result($resaco,0,'y116_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3588,20028,'','".AddSlashes(pg_result($resaco,0,'y116_inscricaomunicipal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3588,20025,'','".AddSlashes(pg_result($resaco,0,'y116_datalancamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3588,20026,'','".AddSlashes(pg_result($resaco,0,'y116_quantidadesolicitada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3588,20027,'','".AddSlashes(pg_result($resaco,0,'y116_quantidadeLiberada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3588,20029,'','".AddSlashes(pg_result($resaco,0,'y116_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3588,20030,'','".AddSlashes(pg_result($resaco,0,'y116_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3588,20210,'','".AddSlashes(pg_result($resaco,0,'y116_codigoaidof'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y116_id=null) { 
      $this->atualizacampos();
     $sql = " update requisicaoaidof set ";
     $virgula = "";
     if(trim($this->y116_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y116_id"])){ 
       $sql  .= $virgula." y116_id = $this->y116_id ";
       $virgula = ",";
       if(trim($this->y116_id) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "y116_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y116_tipodocumento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y116_tipodocumento"])){ 
       $sql  .= $virgula." y116_tipodocumento = $this->y116_tipodocumento ";
       $virgula = ",";
       if(trim($this->y116_tipodocumento) == null ){ 
         $this->erro_sql = " Campo Tipo de Documento não informado.";
         $this->erro_campo = "y116_tipodocumento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y116_codigografica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y116_codigografica"])){ 
       $sql  .= $virgula." y116_codigografica = $this->y116_codigografica ";
       $virgula = ",";
       if(trim($this->y116_codigografica) == null ){ 
         $this->erro_sql = " Campo Código da Gráfica não informado.";
         $this->erro_campo = "y116_codigografica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y116_idusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y116_idusuario"])){ 
        if(trim($this->y116_idusuario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["y116_idusuario"])){ 
           $this->y116_idusuario = "0" ; 
        } 
       $sql  .= $virgula." y116_idusuario = $this->y116_idusuario ";
       $virgula = ",";
     }
     if(trim($this->y116_inscricaomunicipal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y116_inscricaomunicipal"])){ 
       $sql  .= $virgula." y116_inscricaomunicipal = $this->y116_inscricaomunicipal ";
       $virgula = ",";
       if(trim($this->y116_inscricaomunicipal) == null ){ 
         $this->erro_sql = " Campo Inscrição Municipal não informado.";
         $this->erro_campo = "y116_inscricaomunicipal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y116_datalancamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y116_datalancamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y116_datalancamento_dia"] !="") ){ 
       $sql  .= $virgula." y116_datalancamento = '$this->y116_datalancamento' ";
       $virgula = ",";
       if(trim($this->y116_datalancamento) == null ){ 
         $this->erro_sql = " Campo Data do Lançamento não informado.";
         $this->erro_campo = "y116_datalancamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y116_datalancamento_dia"])){ 
         $sql  .= $virgula." y116_datalancamento = null ";
         $virgula = ",";
         if(trim($this->y116_datalancamento) == null ){ 
           $this->erro_sql = " Campo Data do Lançamento não informado.";
           $this->erro_campo = "y116_datalancamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y116_quantidadesolicitada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y116_quantidadesolicitada"])){ 
       $sql  .= $virgula." y116_quantidadesolicitada = $this->y116_quantidadesolicitada ";
       $virgula = ",";
       if(trim($this->y116_quantidadesolicitada) == null ){ 
         $this->erro_sql = " Campo Quantidade Solicitada não informado.";
         $this->erro_campo = "y116_quantidadesolicitada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y116_quantidadeLiberada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y116_quantidadeLiberada"])){ 
        if(trim($this->y116_quantidadeLiberada)=="" && isset($GLOBALS["HTTP_POST_VARS"]["y116_quantidadeLiberada"])){ 
           $this->y116_quantidadeLiberada = "0" ; 
        } 
       $sql  .= $virgula." y116_quantidadeLiberada = $this->y116_quantidadeLiberada ";
       $virgula = ",";
     }
     if(trim($this->y116_status)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y116_status"])){ 
       $sql  .= $virgula." y116_status = '$this->y116_status' ";
       $virgula = ",";
       if(trim($this->y116_status) == null ){ 
         $this->erro_sql = " Campo Status não informado.";
         $this->erro_campo = "y116_status";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y116_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y116_observacao"])){ 
       $sql  .= $virgula." y116_observacao = '$this->y116_observacao' ";
       $virgula = ",";
     }
     if(trim($this->y116_codigoaidof)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y116_codigoaidof"])){ 
        if(trim($this->y116_codigoaidof)=="" && isset($GLOBALS["HTTP_POST_VARS"]["y116_codigoaidof"])){ 
           $this->y116_codigoaidof = "0" ; 
        } 
       $sql  .= $virgula." y116_codigoaidof = $this->y116_codigoaidof ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($y116_id!=null){
       $sql .= " y116_id = $this->y116_id";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->y116_id));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20023,'$this->y116_id','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["y116_id"]) || $this->y116_id != "")
             $resac = db_query("insert into db_acount values($acount,3588,20023,'".AddSlashes(pg_result($resaco,$conresaco,'y116_id'))."','$this->y116_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["y116_tipodocumento"]) || $this->y116_tipodocumento != "")
             $resac = db_query("insert into db_acount values($acount,3588,20024,'".AddSlashes(pg_result($resaco,$conresaco,'y116_tipodocumento'))."','$this->y116_tipodocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["y116_codigografica"]) || $this->y116_codigografica != "")
             $resac = db_query("insert into db_acount values($acount,3588,20049,'".AddSlashes(pg_result($resaco,$conresaco,'y116_codigografica'))."','$this->y116_codigografica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["y116_idusuario"]) || $this->y116_idusuario != "")
             $resac = db_query("insert into db_acount values($acount,3588,20031,'".AddSlashes(pg_result($resaco,$conresaco,'y116_idusuario'))."','$this->y116_idusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["y116_inscricaomunicipal"]) || $this->y116_inscricaomunicipal != "")
             $resac = db_query("insert into db_acount values($acount,3588,20028,'".AddSlashes(pg_result($resaco,$conresaco,'y116_inscricaomunicipal'))."','$this->y116_inscricaomunicipal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["y116_datalancamento"]) || $this->y116_datalancamento != "")
             $resac = db_query("insert into db_acount values($acount,3588,20025,'".AddSlashes(pg_result($resaco,$conresaco,'y116_datalancamento'))."','$this->y116_datalancamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["y116_quantidadesolicitada"]) || $this->y116_quantidadesolicitada != "")
             $resac = db_query("insert into db_acount values($acount,3588,20026,'".AddSlashes(pg_result($resaco,$conresaco,'y116_quantidadesolicitada'))."','$this->y116_quantidadesolicitada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["y116_quantidadeLiberada"]) || $this->y116_quantidadeLiberada != "")
             $resac = db_query("insert into db_acount values($acount,3588,20027,'".AddSlashes(pg_result($resaco,$conresaco,'y116_quantidadeLiberada'))."','$this->y116_quantidadeLiberada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["y116_status"]) || $this->y116_status != "")
             $resac = db_query("insert into db_acount values($acount,3588,20029,'".AddSlashes(pg_result($resaco,$conresaco,'y116_status'))."','$this->y116_status',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["y116_observacao"]) || $this->y116_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3588,20030,'".AddSlashes(pg_result($resaco,$conresaco,'y116_observacao'))."','$this->y116_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["y116_codigoaidof"]) || $this->y116_codigoaidof != "")
             $resac = db_query("insert into db_acount values($acount,3588,20210,'".AddSlashes(pg_result($resaco,$conresaco,'y116_codigoaidof'))."','$this->y116_codigoaidof',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Requisição de AIDOF nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y116_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Requisição de AIDOF nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y116_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y116_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y116_id=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($y116_id));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20023,'$y116_id','E')");
           $resac  = db_query("insert into db_acount values($acount,3588,20023,'','".AddSlashes(pg_result($resaco,$iresaco,'y116_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3588,20024,'','".AddSlashes(pg_result($resaco,$iresaco,'y116_tipodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3588,20049,'','".AddSlashes(pg_result($resaco,$iresaco,'y116_codigografica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3588,20031,'','".AddSlashes(pg_result($resaco,$iresaco,'y116_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3588,20028,'','".AddSlashes(pg_result($resaco,$iresaco,'y116_inscricaomunicipal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3588,20025,'','".AddSlashes(pg_result($resaco,$iresaco,'y116_datalancamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3588,20026,'','".AddSlashes(pg_result($resaco,$iresaco,'y116_quantidadesolicitada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3588,20027,'','".AddSlashes(pg_result($resaco,$iresaco,'y116_quantidadeLiberada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3588,20029,'','".AddSlashes(pg_result($resaco,$iresaco,'y116_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3588,20030,'','".AddSlashes(pg_result($resaco,$iresaco,'y116_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3588,20210,'','".AddSlashes(pg_result($resaco,$iresaco,'y116_codigoaidof'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from requisicaoaidof
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y116_id != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y116_id = $y116_id ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Requisição de AIDOF nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y116_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Requisição de AIDOF nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y116_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y116_id;
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
        $this->erro_sql   = "Record Vazio na Tabela:requisicaoaidof";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $y116_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from requisicaoaidof ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = requisicaoaidof.y116_inscricaomunicipal";
     $sql .= "      left  join db_usuarios  on  db_usuarios.id_usuario = requisicaoaidof.y116_idusuario";
     $sql .= "      inner join notasiss  on  notasiss.q09_codigo = requisicaoaidof.y116_tipodocumento";
     $sql .= "      inner join graficas  on  graficas.y20_grafica = requisicaoaidof.y116_codigografica";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql .= "      inner join gruponotaiss  on  gruponotaiss.q139_sequencial = notasiss.q09_gruponotaiss";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = graficas.y20_grafica";
     $sql .= "      inner join db_usuarios  as b on   b.id_usuario = graficas.y20_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($y116_id!=null ){
         $sql2 .= " where requisicaoaidof.y116_id = $y116_id "; 
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
   function sql_query_file ( $y116_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from requisicaoaidof ";
     $sql2 = "";
     if($dbwhere==""){
       if($y116_id!=null ){
         $sql2 .= " where requisicaoaidof.y116_id = $y116_id "; 
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
   /**
   * Sql com dados da requisicação de aidof
   * 
   * @param string $y116_id
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
  function sql_query_dadosRequisicao($y116_id = null, $campos = '*', $ordem = null, $dbwhere = '') {
    $sSql = 'select ';
    
    if ($campos != '*') {
      
      $campos_sql = split('#', $campos);
      $virgula    = '';
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sSql .= $virgula.$campos_sql[$i];
        $virgula = ',';
      }
    } else {
      $sSql .= $campos;
    }
    
    $sSql .= ' from requisicaoaidof ';
    $sSql .= '      inner join notasiss     on  notasiss.q09_codigo          = requisicaoaidof.y116_tipodocumento';
    $sSql .= '      inner join gruponotaiss on  gruponotaiss.q139_sequencial = notasiss.q09_gruponotaiss';
    $sSql .= '      inner join issbase      on  issbase.q02_inscr            = requisicaoaidof.y116_inscricaomunicipal';
    $sSql .= '      inner join cgm          on  cgm.z01_numcgm               = issbase.q02_numcgm';
    $sSql .= '      left  join db_usuarios  on  db_usuarios.id_usuario       = requisicaoaidof.y116_idusuario';
    
    $sSql2 = '';
    
    if ($dbwhere == '') {
      
      if ($y116_id != null) {
        $sSql2 .= " where requisicaoaidof.y116_id = {$y116_id} ";
      }
    } else if ($dbwhere != '') {
      $sSql2 = " where {$dbwhere} ";
    }
    
    $sSql .= $sSql2;
    
    if ($ordem != null) {
      
      $sSql       .= ' order by ';
      $campos_sql = split("#",$ordem);
      $virgula    = '';
      
      for($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sSql     .= $virgula.$campos_sql[$i];
        $virgula  = ',';
      }
    }
    
    return $sSql;
  }
   /**
   * Retorna Sql para pesquisa das requisições realizadas dos seus clientes
   * pesquisa atravez do Cgm do Escritório
   * 
   * @param integer $iCgm
   * @param string $sCampos
   * @param string $sOrdem
   * @return string
   */
  function sql_query_RequisicoesPorEscritorio($iCgm = null, $iInscricao = null, $sCampos = "*", $sOrdem = null) {
    
    $sSql = "select ";
    
    if ($sCampos != "*" ) {
      
      $sCamposSql = split("#", $sCampos);
      $sVirgula   = "";
      
      for ($i = 0 ; $i < sizeof($sCamposSql); $i++) {
        
        $sSql     .= $sVirgula. $sCamposSql[$i];
        $sVirgula  = ",";
      }
    } else {
      
      $sSql .= $sCampos;
    }
    

    if ((trim($iCgm) > 0) || (trim($iInscricao) > 0)) {
    
      $sWhere = " where ";
      
      if (trim($iCgm) > 0) {
        $sWhere .= ' q10_numcgm = ' . $iCgm;
      }
      
      if (trim($iInscricao) > 0) {
        
        if (trim($iCgm) > 0) {
          $sWhere .= ' and ';
        }
        
        $sWhere .= ' q02_inscr = ' . $iInscricao;
      } 
    }
    
    $sSql .= "  from escrito                                                            ";
    $sSql .= "       inner join issbase         on q02_inscr               = q10_inscr  ";
    $sSql .= "       inner join cgm             on z01_numcgm              = q02_numcgm ";
    $sSql .= "       inner join requisicaoaidof on y116_inscricaomunicipal = q02_inscr  ";
    $sSql .= "       {$sWhere}                                                          ";
    $sSql .= "   and q10_dtfim  is null                                                 ";
    $sSql .= "   and q02_dtbaix is null                                                 ";
  
    if ($sOrdem != null) {
      
      $sSql       .= " order by ";
      $sCamposSql  = split("#", $sOrdem);
      $sVirgula    = "";
      
      for ($i = 0; $i < sizeof($sCamposSql); $i++) {
        
        $sSql     .= $sVirgula . $sCamposSql[$i];
        $sVirgula  = ",";
      }
    }
    
    
    
    return $sSql;
  }
}
?>