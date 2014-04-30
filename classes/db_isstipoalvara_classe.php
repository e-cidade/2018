<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE isstipoalvara
class cl_isstipoalvara { 
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
   var $q98_sequencial = 0; 
   var $q98_documento = 0; 
   var $q98_issgrupotipoalvara = 0; 
   var $q98_descricao = null; 
   var $q98_permitetransformacao = 'f'; 
   var $q98_gerataxa = 'f'; 
   var $q98_instit = 0; 
   var $q98_quantvalidade = 0; 
   var $q98_permiterenovacao = 'f'; 
   var $q98_quantrenovacao = 0; 
   var $q98_permiteimpressao = 'f'; 
   var $q98_tipovalidade = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q98_sequencial = int4 = Sequencial 
                 q98_documento = int4 = Documento Template 
                 q98_issgrupotipoalvara = int4 = Grupo do Alvará 
                 q98_descricao = varchar(200) = Descrição 
                 q98_permitetransformacao = bool = Permite transformação 
                 q98_gerataxa = bool = Gera Taxa 
                 q98_instit = int4 = Instituição 
                 q98_quantvalidade = int4 = Quantidade de validade 
                 q98_permiterenovacao = bool = Permite Renovação 
                 q98_quantrenovacao = int4 = Quantidade de Renovação 
                 q98_permiteimpressao = bool = Permite Impressão 
                 q98_tipovalidade = int4 = Tipo de Validade 
                 ";
   //funcao construtor da classe 
   function cl_isstipoalvara() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("isstipoalvara"); 
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
       $this->q98_sequencial = ($this->q98_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q98_sequencial"]:$this->q98_sequencial);
       $this->q98_documento = ($this->q98_documento == ""?@$GLOBALS["HTTP_POST_VARS"]["q98_documento"]:$this->q98_documento);
       $this->q98_issgrupotipoalvara = ($this->q98_issgrupotipoalvara == ""?@$GLOBALS["HTTP_POST_VARS"]["q98_issgrupotipoalvara"]:$this->q98_issgrupotipoalvara);
       $this->q98_descricao = ($this->q98_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["q98_descricao"]:$this->q98_descricao);
       $this->q98_permitetransformacao = ($this->q98_permitetransformacao == "f"?@$GLOBALS["HTTP_POST_VARS"]["q98_permitetransformacao"]:$this->q98_permitetransformacao);
       $this->q98_gerataxa = ($this->q98_gerataxa == "f"?@$GLOBALS["HTTP_POST_VARS"]["q98_gerataxa"]:$this->q98_gerataxa);
       $this->q98_instit = ($this->q98_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["q98_instit"]:$this->q98_instit);
       $this->q98_quantvalidade = ($this->q98_quantvalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["q98_quantvalidade"]:$this->q98_quantvalidade);
       $this->q98_permiterenovacao = ($this->q98_permiterenovacao == "f"?@$GLOBALS["HTTP_POST_VARS"]["q98_permiterenovacao"]:$this->q98_permiterenovacao);
       $this->q98_quantrenovacao = ($this->q98_quantrenovacao == ""?@$GLOBALS["HTTP_POST_VARS"]["q98_quantrenovacao"]:$this->q98_quantrenovacao);
       $this->q98_permiteimpressao = ($this->q98_permiteimpressao == "f"?@$GLOBALS["HTTP_POST_VARS"]["q98_permiteimpressao"]:$this->q98_permiteimpressao);
       $this->q98_tipovalidade = ($this->q98_tipovalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["q98_tipovalidade"]:$this->q98_tipovalidade);
     }else{
       $this->q98_sequencial = ($this->q98_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q98_sequencial"]:$this->q98_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q98_sequencial){ 
      $this->atualizacampos();
     if($this->q98_documento == null ){ 
       $this->erro_sql = " Campo Documento Template nao Informado.";
       $this->erro_campo = "q98_documento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q98_issgrupotipoalvara == null ){ 
       $this->erro_sql = " Campo Grupo do Alvará nao Informado.";
       $this->erro_campo = "q98_issgrupotipoalvara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q98_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "q98_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q98_permitetransformacao == null ){ 
       $this->erro_sql = " Campo Permite transformação nao Informado.";
       $this->erro_campo = "q98_permitetransformacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q98_gerataxa == null ){ 
       $this->erro_sql = " Campo Gera Taxa nao Informado.";
       $this->erro_campo = "q98_gerataxa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q98_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "q98_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q98_quantvalidade == null ){ 
       $this->q98_quantvalidade = "0";
     }
     if($this->q98_permiterenovacao == null ){ 
       $this->q98_permiterenovacao = "f";
     }
     if($this->q98_quantrenovacao == null ){ 
       $this->q98_quantrenovacao = "0";
     }
     if($this->q98_permiteimpressao == null ){ 
       $this->erro_sql = " Campo Permite Impressão nao Informado.";
       $this->erro_campo = "q98_permiteimpressao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q98_tipovalidade == null ){ 
       $this->erro_sql = " Campo Tipo de Validade nao Informado.";
       $this->erro_campo = "q98_tipovalidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q98_sequencial == "" || $q98_sequencial == null ){
       $result = db_query("select nextval('isstipoalvara_q98_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: isstipoalvara_q98_sequencial_seq do campo: q98_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q98_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from isstipoalvara_q98_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q98_sequencial)){
         $this->erro_sql = " Campo q98_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q98_sequencial = $q98_sequencial; 
       }
     }
     if(($this->q98_sequencial == null) || ($this->q98_sequencial == "") ){ 
       $this->erro_sql = " Campo q98_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into isstipoalvara(
                                       q98_sequencial 
                                      ,q98_documento 
                                      ,q98_issgrupotipoalvara 
                                      ,q98_descricao 
                                      ,q98_permitetransformacao 
                                      ,q98_gerataxa 
                                      ,q98_instit 
                                      ,q98_quantvalidade 
                                      ,q98_permiterenovacao 
                                      ,q98_quantrenovacao 
                                      ,q98_permiteimpressao 
                                      ,q98_tipovalidade 
                       )
                values (
                                $this->q98_sequencial 
                               ,$this->q98_documento 
                               ,$this->q98_issgrupotipoalvara 
                               ,'$this->q98_descricao' 
                               ,'$this->q98_permitetransformacao' 
                               ,'$this->q98_gerataxa' 
                               ,$this->q98_instit 
                               ,$this->q98_quantvalidade 
                               ,'$this->q98_permiterenovacao' 
                               ,$this->q98_quantrenovacao 
                               ,'$this->q98_permiteimpressao' 
                               ,$this->q98_tipovalidade 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipos de Alvarás ($this->q98_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipos de Alvarás já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipos de Alvarás ($this->q98_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q98_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q98_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18304,'$this->q98_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3238,18304,'','".AddSlashes(pg_result($resaco,0,'q98_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3238,18313,'','".AddSlashes(pg_result($resaco,0,'q98_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3238,18305,'','".AddSlashes(pg_result($resaco,0,'q98_issgrupotipoalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3238,18306,'','".AddSlashes(pg_result($resaco,0,'q98_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3238,18307,'','".AddSlashes(pg_result($resaco,0,'q98_permitetransformacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3238,18308,'','".AddSlashes(pg_result($resaco,0,'q98_gerataxa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3238,18309,'','".AddSlashes(pg_result($resaco,0,'q98_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3238,18310,'','".AddSlashes(pg_result($resaco,0,'q98_quantvalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3238,18311,'','".AddSlashes(pg_result($resaco,0,'q98_permiterenovacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3238,18312,'','".AddSlashes(pg_result($resaco,0,'q98_quantrenovacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3238,18316,'','".AddSlashes(pg_result($resaco,0,'q98_permiteimpressao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3238,18349,'','".AddSlashes(pg_result($resaco,0,'q98_tipovalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q98_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update isstipoalvara set ";
     $virgula = "";
     if(trim($this->q98_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q98_sequencial"])){ 
       $sql  .= $virgula." q98_sequencial = $this->q98_sequencial ";
       $virgula = ",";
       if(trim($this->q98_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q98_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q98_documento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q98_documento"])){ 
       $sql  .= $virgula." q98_documento = $this->q98_documento ";
       $virgula = ",";
       if(trim($this->q98_documento) == null ){ 
         $this->erro_sql = " Campo Documento Template nao Informado.";
         $this->erro_campo = "q98_documento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q98_issgrupotipoalvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q98_issgrupotipoalvara"])){ 
       $sql  .= $virgula." q98_issgrupotipoalvara = $this->q98_issgrupotipoalvara ";
       $virgula = ",";
       if(trim($this->q98_issgrupotipoalvara) == null ){ 
         $this->erro_sql = " Campo Grupo do Alvará nao Informado.";
         $this->erro_campo = "q98_issgrupotipoalvara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q98_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q98_descricao"])){ 
       $sql  .= $virgula." q98_descricao = '$this->q98_descricao' ";
       $virgula = ",";
       if(trim($this->q98_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "q98_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q98_permitetransformacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q98_permitetransformacao"])){ 
       $sql  .= $virgula." q98_permitetransformacao = '$this->q98_permitetransformacao' ";
       $virgula = ",";
       if(trim($this->q98_permitetransformacao) == null ){ 
         $this->erro_sql = " Campo Permite transformação nao Informado.";
         $this->erro_campo = "q98_permitetransformacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q98_gerataxa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q98_gerataxa"])){ 
       $sql  .= $virgula." q98_gerataxa = '$this->q98_gerataxa' ";
       $virgula = ",";
       if(trim($this->q98_gerataxa) == null ){ 
         $this->erro_sql = " Campo Gera Taxa nao Informado.";
         $this->erro_campo = "q98_gerataxa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q98_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q98_instit"])){ 
       $sql  .= $virgula." q98_instit = $this->q98_instit ";
       $virgula = ",";
       if(trim($this->q98_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "q98_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q98_quantvalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q98_quantvalidade"])){ 
        if(trim($this->q98_quantvalidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q98_quantvalidade"])){ 
           $this->q98_quantvalidade = "0" ; 
        } 
       $sql  .= $virgula." q98_quantvalidade = $this->q98_quantvalidade ";
       $virgula = ",";
     }
     if(trim($this->q98_permiterenovacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q98_permiterenovacao"])){ 
       $sql  .= $virgula." q98_permiterenovacao = '$this->q98_permiterenovacao' ";
       $virgula = ",";
     }
     if(trim($this->q98_quantrenovacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q98_quantrenovacao"])){ 
        if(trim($this->q98_quantrenovacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q98_quantrenovacao"])){ 
           $this->q98_quantrenovacao = "0" ; 
        } 
       $sql  .= $virgula." q98_quantrenovacao = $this->q98_quantrenovacao ";
       $virgula = ",";
     }
     if(trim($this->q98_permiteimpressao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q98_permiteimpressao"])){ 
       $sql  .= $virgula." q98_permiteimpressao = '$this->q98_permiteimpressao' ";
       $virgula = ",";
       if(trim($this->q98_permiteimpressao) == null ){ 
         $this->erro_sql = " Campo Permite Impressão nao Informado.";
         $this->erro_campo = "q98_permiteimpressao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q98_tipovalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q98_tipovalidade"])){ 
       $sql  .= $virgula." q98_tipovalidade = $this->q98_tipovalidade ";
       $virgula = ",";
       if(trim($this->q98_tipovalidade) == null ){ 
         $this->erro_sql = " Campo Tipo de Validade nao Informado.";
         $this->erro_campo = "q98_tipovalidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q98_sequencial!=null){
       $sql .= " q98_sequencial = $this->q98_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q98_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18304,'$this->q98_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q98_sequencial"]) || $this->q98_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3238,18304,'".AddSlashes(pg_result($resaco,$conresaco,'q98_sequencial'))."','$this->q98_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q98_documento"]) || $this->q98_documento != "")
           $resac = db_query("insert into db_acount values($acount,3238,18313,'".AddSlashes(pg_result($resaco,$conresaco,'q98_documento'))."','$this->q98_documento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q98_issgrupotipoalvara"]) || $this->q98_issgrupotipoalvara != "")
           $resac = db_query("insert into db_acount values($acount,3238,18305,'".AddSlashes(pg_result($resaco,$conresaco,'q98_issgrupotipoalvara'))."','$this->q98_issgrupotipoalvara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q98_descricao"]) || $this->q98_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3238,18306,'".AddSlashes(pg_result($resaco,$conresaco,'q98_descricao'))."','$this->q98_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q98_permitetransformacao"]) || $this->q98_permitetransformacao != "")
           $resac = db_query("insert into db_acount values($acount,3238,18307,'".AddSlashes(pg_result($resaco,$conresaco,'q98_permitetransformacao'))."','$this->q98_permitetransformacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q98_gerataxa"]) || $this->q98_gerataxa != "")
           $resac = db_query("insert into db_acount values($acount,3238,18308,'".AddSlashes(pg_result($resaco,$conresaco,'q98_gerataxa'))."','$this->q98_gerataxa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q98_instit"]) || $this->q98_instit != "")
           $resac = db_query("insert into db_acount values($acount,3238,18309,'".AddSlashes(pg_result($resaco,$conresaco,'q98_instit'))."','$this->q98_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q98_quantvalidade"]) || $this->q98_quantvalidade != "")
           $resac = db_query("insert into db_acount values($acount,3238,18310,'".AddSlashes(pg_result($resaco,$conresaco,'q98_quantvalidade'))."','$this->q98_quantvalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q98_permiterenovacao"]) || $this->q98_permiterenovacao != "")
           $resac = db_query("insert into db_acount values($acount,3238,18311,'".AddSlashes(pg_result($resaco,$conresaco,'q98_permiterenovacao'))."','$this->q98_permiterenovacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q98_quantrenovacao"]) || $this->q98_quantrenovacao != "")
           $resac = db_query("insert into db_acount values($acount,3238,18312,'".AddSlashes(pg_result($resaco,$conresaco,'q98_quantrenovacao'))."','$this->q98_quantrenovacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q98_permiteimpressao"]) || $this->q98_permiteimpressao != "")
           $resac = db_query("insert into db_acount values($acount,3238,18316,'".AddSlashes(pg_result($resaco,$conresaco,'q98_permiteimpressao'))."','$this->q98_permiteimpressao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q98_tipovalidade"]) || $this->q98_tipovalidade != "")
           $resac = db_query("insert into db_acount values($acount,3238,18349,'".AddSlashes(pg_result($resaco,$conresaco,'q98_tipovalidade'))."','$this->q98_tipovalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Alvarás nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q98_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Alvarás nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q98_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q98_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q98_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q98_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18304,'$q98_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3238,18304,'','".AddSlashes(pg_result($resaco,$iresaco,'q98_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3238,18313,'','".AddSlashes(pg_result($resaco,$iresaco,'q98_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3238,18305,'','".AddSlashes(pg_result($resaco,$iresaco,'q98_issgrupotipoalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3238,18306,'','".AddSlashes(pg_result($resaco,$iresaco,'q98_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3238,18307,'','".AddSlashes(pg_result($resaco,$iresaco,'q98_permitetransformacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3238,18308,'','".AddSlashes(pg_result($resaco,$iresaco,'q98_gerataxa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3238,18309,'','".AddSlashes(pg_result($resaco,$iresaco,'q98_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3238,18310,'','".AddSlashes(pg_result($resaco,$iresaco,'q98_quantvalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3238,18311,'','".AddSlashes(pg_result($resaco,$iresaco,'q98_permiterenovacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3238,18312,'','".AddSlashes(pg_result($resaco,$iresaco,'q98_quantrenovacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3238,18316,'','".AddSlashes(pg_result($resaco,$iresaco,'q98_permiteimpressao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3238,18349,'','".AddSlashes(pg_result($resaco,$iresaco,'q98_tipovalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from isstipoalvara
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q98_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q98_sequencial = $q98_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Alvarás nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q98_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Alvarás nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q98_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q98_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:isstipoalvara";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q98_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isstipoalvara ";
     $sql .= "      inner join db_documentotemplate  on  db_documentotemplate.db82_sequencial = isstipoalvara.q98_documento";
     $sql .= "      inner join issgrupotipoalvara  on  issgrupotipoalvara.q97_sequencial = isstipoalvara.q98_issgrupotipoalvara";
     $sql .= "      inner join db_config  on  db_config.codigo = db_documentotemplate.db82_instit";
     $sql .= "      inner join db_documentotemplatetipo  on  db_documentotemplatetipo.db80_sequencial = db_documentotemplate.db82_templatetipo";
     $sql2 = "";
     if($dbwhere==""){
       if($q98_sequencial!=null ){
         $sql2 .= " where isstipoalvara.q98_sequencial = $q98_sequencial "; 
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
   function sql_query_file ( $q98_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isstipoalvara ";
     $sql2 = "";
     if($dbwhere==""){
       if($q98_sequencial!=null ){
         $sql2 .= " where isstipoalvara.q98_sequencial = $q98_sequencial "; 
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
   function sql_query_tipocomalvaravinculado($q98_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isstipoalvara ";
     $sql .= "      inner join db_documentotemplate      on  db_documentotemplate.db82_sequencial     = isstipoalvara.q98_documento           ";
     $sql .= "      inner join issgrupotipoalvara        on  issgrupotipoalvara.q97_sequencial        = isstipoalvara.q98_issgrupotipoalvara  ";
     $sql .= "      inner join db_config                 on  db_config.codigo                         = db_documentotemplate.db82_instit      ";
     $sql .= "      inner join db_documentotemplatetipo  on  db_documentotemplatetipo.db80_sequencial = db_documentotemplate.db82_templatetipo";
     $sql .= "      left  join issalvara                 on issalvara.q123_isstipoalvara              = isstipoalvara.q98_sequencial          ";
     $sql2 = "";
     if($dbwhere==""){
       if($q98_sequencial!=null ){
         $sql2 .= " where isstipoalvara.q98_sequencial = $q98_sequencial "; 
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