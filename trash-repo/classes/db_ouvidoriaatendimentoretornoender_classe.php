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

//MODULO: ouvidoria
//CLASSE DA ENTIDADE ouvidoriaatendimentoretornoender
class cl_ouvidoriaatendimentoretornoender { 
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
   var $ov12_sequencial = 0; 
   var $ov12_ouvidoriaantendimento = 0; 
   var $ov12_endereco = null; 
   var $ov12_numero = 0; 
   var $ov12_compl = null; 
   var $ov12_munic = null; 
   var $ov12_bairro = null; 
   var $ov12_uf = null; 
   var $ov12_cep = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ov12_sequencial = int4 = Sequencial 
                 ov12_ouvidoriaantendimento = int4 = Atendimento 
                 ov12_endereco = varchar(100) = Endereço 
                 ov12_numero = int4 = Número 
                 ov12_compl = varchar(100) = Complemento 
                 ov12_munic = varchar(100) = Município 
                 ov12_bairro = varchar(100) = Bairro 
                 ov12_uf = char(2) = UF 
                 ov12_cep = varchar(8) = CEP 
                 ";
   //funcao construtor da classe 
   function cl_ouvidoriaatendimentoretornoender() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ouvidoriaatendimentoretornoender"); 
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
       $this->ov12_sequencial = ($this->ov12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov12_sequencial"]:$this->ov12_sequencial);
       $this->ov12_ouvidoriaantendimento = ($this->ov12_ouvidoriaantendimento == ""?@$GLOBALS["HTTP_POST_VARS"]["ov12_ouvidoriaantendimento"]:$this->ov12_ouvidoriaantendimento);
       $this->ov12_endereco = ($this->ov12_endereco == ""?@$GLOBALS["HTTP_POST_VARS"]["ov12_endereco"]:$this->ov12_endereco);
       $this->ov12_numero = ($this->ov12_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ov12_numero"]:$this->ov12_numero);
       $this->ov12_compl = ($this->ov12_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["ov12_compl"]:$this->ov12_compl);
       $this->ov12_munic = ($this->ov12_munic == ""?@$GLOBALS["HTTP_POST_VARS"]["ov12_munic"]:$this->ov12_munic);
       $this->ov12_bairro = ($this->ov12_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["ov12_bairro"]:$this->ov12_bairro);
       $this->ov12_uf = ($this->ov12_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["ov12_uf"]:$this->ov12_uf);
       $this->ov12_cep = ($this->ov12_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["ov12_cep"]:$this->ov12_cep);
     }else{
       $this->ov12_sequencial = ($this->ov12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov12_sequencial"]:$this->ov12_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ov12_sequencial){ 
      $this->atualizacampos();
     if($this->ov12_ouvidoriaantendimento == null ){ 
       $this->erro_sql = " Campo Atendimento nao Informado.";
       $this->erro_campo = "ov12_ouvidoriaantendimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov12_endereco == null ){ 
       $this->erro_sql = " Campo Endereço nao Informado.";
       $this->erro_campo = "ov12_endereco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov12_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "ov12_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov12_munic == null ){ 
       $this->erro_sql = " Campo Município nao Informado.";
       $this->erro_campo = "ov12_munic";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov12_bairro == null ){ 
       $this->erro_sql = " Campo Bairro nao Informado.";
       $this->erro_campo = "ov12_bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov12_uf == null ){ 
       $this->erro_sql = " Campo UF nao Informado.";
       $this->erro_campo = "ov12_uf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ov12_sequencial == "" || $ov12_sequencial == null ){
       $result = db_query("select nextval('ouvidoriaatendimentoretornoender_ov12_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ouvidoriaatendimentoretornoender_ov12_sequencial_seq do campo: ov12_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ov12_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ouvidoriaatendimentoretornoender_ov12_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ov12_sequencial)){
         $this->erro_sql = " Campo ov12_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ov12_sequencial = $ov12_sequencial; 
       }
     }
     if(($this->ov12_sequencial == null) || ($this->ov12_sequencial == "") ){ 
       $this->erro_sql = " Campo ov12_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ouvidoriaatendimentoretornoender(
                                       ov12_sequencial 
                                      ,ov12_ouvidoriaantendimento 
                                      ,ov12_endereco 
                                      ,ov12_numero 
                                      ,ov12_compl 
                                      ,ov12_munic 
                                      ,ov12_bairro 
                                      ,ov12_uf 
                                      ,ov12_cep 
                       )
                values (
                                $this->ov12_sequencial 
                               ,$this->ov12_ouvidoriaantendimento 
                               ,'$this->ov12_endereco' 
                               ,$this->ov12_numero 
                               ,'$this->ov12_compl' 
                               ,'$this->ov12_munic' 
                               ,'$this->ov12_bairro' 
                               ,'$this->ov12_uf' 
                               ,'$this->ov12_cep' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Endereços de Retorno do Atendimento da Ouvidoria ($this->ov12_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Endereços de Retorno do Atendimento da Ouvidoria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Endereços de Retorno do Atendimento da Ouvidoria ($this->ov12_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov12_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ov12_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14794,'$this->ov12_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2604,14794,'','".AddSlashes(pg_result($resaco,0,'ov12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2604,14795,'','".AddSlashes(pg_result($resaco,0,'ov12_ouvidoriaantendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2604,14796,'','".AddSlashes(pg_result($resaco,0,'ov12_endereco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2604,14797,'','".AddSlashes(pg_result($resaco,0,'ov12_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2604,14798,'','".AddSlashes(pg_result($resaco,0,'ov12_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2604,14799,'','".AddSlashes(pg_result($resaco,0,'ov12_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2604,14800,'','".AddSlashes(pg_result($resaco,0,'ov12_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2604,14801,'','".AddSlashes(pg_result($resaco,0,'ov12_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2604,14826,'','".AddSlashes(pg_result($resaco,0,'ov12_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ov12_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ouvidoriaatendimentoretornoender set ";
     $virgula = "";
     if(trim($this->ov12_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov12_sequencial"])){ 
       $sql  .= $virgula." ov12_sequencial = $this->ov12_sequencial ";
       $virgula = ",";
       if(trim($this->ov12_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ov12_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov12_ouvidoriaantendimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov12_ouvidoriaantendimento"])){ 
       $sql  .= $virgula." ov12_ouvidoriaantendimento = $this->ov12_ouvidoriaantendimento ";
       $virgula = ",";
       if(trim($this->ov12_ouvidoriaantendimento) == null ){ 
         $this->erro_sql = " Campo Atendimento nao Informado.";
         $this->erro_campo = "ov12_ouvidoriaantendimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov12_endereco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov12_endereco"])){ 
       $sql  .= $virgula." ov12_endereco = '$this->ov12_endereco' ";
       $virgula = ",";
       if(trim($this->ov12_endereco) == null ){ 
         $this->erro_sql = " Campo Endereço nao Informado.";
         $this->erro_campo = "ov12_endereco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov12_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov12_numero"])){ 
       $sql  .= $virgula." ov12_numero = $this->ov12_numero ";
       $virgula = ",";
       if(trim($this->ov12_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "ov12_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov12_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov12_compl"])){ 
       $sql  .= $virgula." ov12_compl = '$this->ov12_compl' ";
       $virgula = ",";
     }
     if(trim($this->ov12_munic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov12_munic"])){ 
       $sql  .= $virgula." ov12_munic = '$this->ov12_munic' ";
       $virgula = ",";
       if(trim($this->ov12_munic) == null ){ 
         $this->erro_sql = " Campo Município nao Informado.";
         $this->erro_campo = "ov12_munic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov12_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov12_bairro"])){ 
       $sql  .= $virgula." ov12_bairro = '$this->ov12_bairro' ";
       $virgula = ",";
       if(trim($this->ov12_bairro) == null ){ 
         $this->erro_sql = " Campo Bairro nao Informado.";
         $this->erro_campo = "ov12_bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov12_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov12_uf"])){ 
       $sql  .= $virgula." ov12_uf = '$this->ov12_uf' ";
       $virgula = ",";
       if(trim($this->ov12_uf) == null ){ 
         $this->erro_sql = " Campo UF nao Informado.";
         $this->erro_campo = "ov12_uf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov12_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov12_cep"])){ 
       $sql  .= $virgula." ov12_cep = '$this->ov12_cep' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ov12_sequencial!=null){
       $sql .= " ov12_sequencial = $this->ov12_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ov12_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14794,'$this->ov12_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov12_sequencial"]) || $this->ov12_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2604,14794,'".AddSlashes(pg_result($resaco,$conresaco,'ov12_sequencial'))."','$this->ov12_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov12_ouvidoriaantendimento"]) || $this->ov12_ouvidoriaantendimento != "")
           $resac = db_query("insert into db_acount values($acount,2604,14795,'".AddSlashes(pg_result($resaco,$conresaco,'ov12_ouvidoriaantendimento'))."','$this->ov12_ouvidoriaantendimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov12_endereco"]) || $this->ov12_endereco != "")
           $resac = db_query("insert into db_acount values($acount,2604,14796,'".AddSlashes(pg_result($resaco,$conresaco,'ov12_endereco'))."','$this->ov12_endereco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov12_numero"]) || $this->ov12_numero != "")
           $resac = db_query("insert into db_acount values($acount,2604,14797,'".AddSlashes(pg_result($resaco,$conresaco,'ov12_numero'))."','$this->ov12_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov12_compl"]) || $this->ov12_compl != "")
           $resac = db_query("insert into db_acount values($acount,2604,14798,'".AddSlashes(pg_result($resaco,$conresaco,'ov12_compl'))."','$this->ov12_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov12_munic"]) || $this->ov12_munic != "")
           $resac = db_query("insert into db_acount values($acount,2604,14799,'".AddSlashes(pg_result($resaco,$conresaco,'ov12_munic'))."','$this->ov12_munic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov12_bairro"]) || $this->ov12_bairro != "")
           $resac = db_query("insert into db_acount values($acount,2604,14800,'".AddSlashes(pg_result($resaco,$conresaco,'ov12_bairro'))."','$this->ov12_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov12_uf"]) || $this->ov12_uf != "")
           $resac = db_query("insert into db_acount values($acount,2604,14801,'".AddSlashes(pg_result($resaco,$conresaco,'ov12_uf'))."','$this->ov12_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov12_cep"]) || $this->ov12_cep != "")
           $resac = db_query("insert into db_acount values($acount,2604,14826,'".AddSlashes(pg_result($resaco,$conresaco,'ov12_cep'))."','$this->ov12_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Endereços de Retorno do Atendimento da Ouvidoria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov12_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Endereços de Retorno do Atendimento da Ouvidoria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ov12_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ov12_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14794,'$ov12_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2604,14794,'','".AddSlashes(pg_result($resaco,$iresaco,'ov12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2604,14795,'','".AddSlashes(pg_result($resaco,$iresaco,'ov12_ouvidoriaantendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2604,14796,'','".AddSlashes(pg_result($resaco,$iresaco,'ov12_endereco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2604,14797,'','".AddSlashes(pg_result($resaco,$iresaco,'ov12_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2604,14798,'','".AddSlashes(pg_result($resaco,$iresaco,'ov12_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2604,14799,'','".AddSlashes(pg_result($resaco,$iresaco,'ov12_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2604,14800,'','".AddSlashes(pg_result($resaco,$iresaco,'ov12_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2604,14801,'','".AddSlashes(pg_result($resaco,$iresaco,'ov12_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2604,14826,'','".AddSlashes(pg_result($resaco,$iresaco,'ov12_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ouvidoriaatendimentoretornoender
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ov12_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ov12_sequencial = $ov12_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Endereços de Retorno do Atendimento da Ouvidoria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ov12_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Endereços de Retorno do Atendimento da Ouvidoria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ov12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ov12_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ouvidoriaatendimentoretornoender";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ov12_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ouvidoriaatendimentoretornoender ";
     $sql .= "      inner join ouvidoriaatendimento  on  ouvidoriaatendimento.ov01_sequencial = ouvidoriaatendimentoretornoender.ov12_ouvidoriaantendimento";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = ouvidoriaatendimento.ov01_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = ouvidoriaatendimento.ov01_depart";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = ouvidoriaatendimento.ov01_tipoprocesso";
     $sql .= "      inner join tipoidentificacao  on  tipoidentificacao.ov05_sequencial = ouvidoriaatendimento.ov01_tipoidentificacao";
     $sql .= "      inner join formareclamacao  on  formareclamacao.p42_sequencial = ouvidoriaatendimento.ov01_formareclamacao";
     $sql .= "      inner join situacaoouvidoriaatendimento  on  situacaoouvidoriaatendimento.ov18_sequencial = ouvidoriaatendimento.ov01_situacaoouvidoriaatendimento";
     $sql2 = "";
     if($dbwhere==""){
       if($ov12_sequencial!=null ){
         $sql2 .= " where ouvidoriaatendimentoretornoender.ov12_sequencial = $ov12_sequencial "; 
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
   function sql_query_file ( $ov12_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ouvidoriaatendimentoretornoender ";
     $sql2 = "";
     if($dbwhere==""){
       if($ov12_sequencial!=null ){
         $sql2 .= " where ouvidoriaatendimentoretornoender.ov12_sequencial = $ov12_sequencial "; 
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