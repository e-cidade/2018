<?
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

//MODULO: Compras
//CLASSE DA ENTIDADE pcmater
class cl_pcmater { 
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
   var $pc01_codmater = 0; 
   var $pc01_descrmater = null; 
   var $pc01_complmater = null; 
   var $pc01_codsubgrupo = 0; 
   var $pc01_ativo = 'f'; 
   var $pc01_conversao = 'f'; 
   var $pc01_id_usuario = 0; 
   var $pc01_libaut = 'f'; 
   var $pc01_servico = 'f'; 
   var $pc01_veiculo = 'f'; 
   var $pc01_fraciona = 'f'; 
   var $pc01_validademinima = 'f'; 
   var $pc01_obrigatorio = 'f'; 
   var $pc01_liberaresumo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc01_codmater = int4 = Código do Material 
                 pc01_descrmater = varchar(80) = Descrição do Material 
                 pc01_complmater = text = Complemento Material 
                 pc01_codsubgrupo = int4 = Código do Sub-Grupo 
                 pc01_ativo = bool = Material inativo 
                 pc01_conversao = bool = Conversão 
                 pc01_id_usuario = int4 = Cod. Usuário 
                 pc01_libaut = bool = Liberado para Autorização de Empenho 
                 pc01_servico = bool = Serviço 
                 pc01_veiculo = bool = Veículo 
                 pc01_fraciona = bool = Fraciona 
                 pc01_validademinima = bool = Validade Mínima 
                 pc01_obrigatorio = bool = Obrigatório 
                 pc01_liberaresumo = bool = Liberar Resumo 
                 ";
   //funcao construtor da classe 
   function cl_pcmater() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcmater"); 
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
       $this->pc01_codmater = ($this->pc01_codmater == ""?@$GLOBALS["HTTP_POST_VARS"]["pc01_codmater"]:$this->pc01_codmater);
       $this->pc01_descrmater = ($this->pc01_descrmater == ""?@$GLOBALS["HTTP_POST_VARS"]["pc01_descrmater"]:$this->pc01_descrmater);
       $this->pc01_complmater = ($this->pc01_complmater == ""?@$GLOBALS["HTTP_POST_VARS"]["pc01_complmater"]:$this->pc01_complmater);
       $this->pc01_codsubgrupo = ($this->pc01_codsubgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc01_codsubgrupo"]:$this->pc01_codsubgrupo);
       $this->pc01_ativo = ($this->pc01_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc01_ativo"]:$this->pc01_ativo);
       $this->pc01_conversao = ($this->pc01_conversao == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc01_conversao"]:$this->pc01_conversao);
       $this->pc01_id_usuario = ($this->pc01_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc01_id_usuario"]:$this->pc01_id_usuario);
       $this->pc01_libaut = ($this->pc01_libaut == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc01_libaut"]:$this->pc01_libaut);
       $this->pc01_servico = ($this->pc01_servico == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc01_servico"]:$this->pc01_servico);
       $this->pc01_veiculo = ($this->pc01_veiculo == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc01_veiculo"]:$this->pc01_veiculo);
       $this->pc01_fraciona = ($this->pc01_fraciona == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc01_fraciona"]:$this->pc01_fraciona);
       $this->pc01_validademinima = ($this->pc01_validademinima == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc01_validademinima"]:$this->pc01_validademinima);
       $this->pc01_obrigatorio = ($this->pc01_obrigatorio == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc01_obrigatorio"]:$this->pc01_obrigatorio);
       $this->pc01_liberaresumo = ($this->pc01_liberaresumo == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc01_liberaresumo"]:$this->pc01_liberaresumo);
     }else{
       $this->pc01_codmater = ($this->pc01_codmater == ""?@$GLOBALS["HTTP_POST_VARS"]["pc01_codmater"]:$this->pc01_codmater);
     }
   }
   // funcao para inclusao
   function incluir ($pc01_codmater){ 
      $this->atualizacampos();
     if($this->pc01_descrmater == null ){ 
       $this->erro_sql = " Campo Descrição do Material nao Informado.";
       $this->erro_campo = "pc01_descrmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc01_codsubgrupo == null ){ 
       $this->erro_sql = " Campo Código do Sub-Grupo nao Informado.";
       $this->erro_campo = "pc01_codsubgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc01_ativo == null ){ 
       $this->erro_sql = " Campo Material inativo nao Informado.";
       $this->erro_campo = "pc01_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc01_conversao == null ){ 
       $this->erro_sql = " Campo Conversão nao Informado.";
       $this->erro_campo = "pc01_conversao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc01_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "pc01_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc01_libaut == null ){ 
       $this->erro_sql = " Campo Liberado para Autorização de Empenho nao Informado.";
       $this->erro_campo = "pc01_libaut";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc01_servico == null ){ 
       $this->erro_sql = " Campo Serviço nao Informado.";
       $this->erro_campo = "pc01_servico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc01_veiculo == null ){ 
       $this->erro_sql = " Campo Veículo nao Informado.";
       $this->erro_campo = "pc01_veiculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc01_fraciona == null ){ 
       $this->erro_sql = " Campo Fraciona nao Informado.";
       $this->erro_campo = "pc01_fraciona";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc01_validademinima == null ){ 
       $this->erro_sql = " Campo Validade Mínima nao Informado.";
       $this->erro_campo = "pc01_validademinima";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc01_obrigatorio == null ){ 
       $this->erro_sql = " Campo Obrigatório nao Informado.";
       $this->erro_campo = "pc01_obrigatorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc01_liberaresumo == null ){ 
       $this->erro_sql = " Campo Liberar Resumo nao Informado.";
       $this->erro_campo = "pc01_liberaresumo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc01_codmater == "" || $pc01_codmater == null ){
       $result = db_query("select nextval('pcmater_pc01_codmater_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcmater_pc01_codmater_seq do campo: pc01_codmater"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc01_codmater = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pcmater_pc01_codmater_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc01_codmater)){
         $this->erro_sql = " Campo pc01_codmater maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc01_codmater = $pc01_codmater; 
       }
     }
     if(($this->pc01_codmater == null) || ($this->pc01_codmater == "") ){ 
       $this->erro_sql = " Campo pc01_codmater nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcmater(
                                       pc01_codmater 
                                      ,pc01_descrmater 
                                      ,pc01_complmater 
                                      ,pc01_codsubgrupo 
                                      ,pc01_ativo 
                                      ,pc01_conversao 
                                      ,pc01_id_usuario 
                                      ,pc01_libaut 
                                      ,pc01_servico 
                                      ,pc01_veiculo 
                                      ,pc01_fraciona 
                                      ,pc01_validademinima 
                                      ,pc01_obrigatorio 
                                      ,pc01_liberaresumo 
                       )
                values (
                                $this->pc01_codmater 
                               ,'$this->pc01_descrmater' 
                               ,'$this->pc01_complmater' 
                               ,$this->pc01_codsubgrupo 
                               ,'$this->pc01_ativo' 
                               ,'$this->pc01_conversao' 
                               ,$this->pc01_id_usuario 
                               ,'$this->pc01_libaut' 
                               ,'$this->pc01_servico' 
                               ,'$this->pc01_veiculo' 
                               ,'$this->pc01_fraciona' 
                               ,'$this->pc01_validademinima' 
                               ,'$this->pc01_obrigatorio' 
                               ,'$this->pc01_liberaresumo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Materiais ($this->pc01_codmater) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Materiais já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Materiais ($this->pc01_codmater) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc01_codmater;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc01_codmater));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5491,'$this->pc01_codmater','I')");
       $resac = db_query("insert into db_acount values($acount,855,5491,'','".AddSlashes(pg_result($resaco,0,'pc01_codmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,855,5492,'','".AddSlashes(pg_result($resaco,0,'pc01_descrmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,855,5493,'','".AddSlashes(pg_result($resaco,0,'pc01_complmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,855,5494,'','".AddSlashes(pg_result($resaco,0,'pc01_codsubgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,855,6602,'','".AddSlashes(pg_result($resaco,0,'pc01_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,855,6761,'','".AddSlashes(pg_result($resaco,0,'pc01_conversao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,855,7425,'','".AddSlashes(pg_result($resaco,0,'pc01_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,855,8068,'','".AddSlashes(pg_result($resaco,0,'pc01_libaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,855,10556,'','".AddSlashes(pg_result($resaco,0,'pc01_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,855,11473,'','".AddSlashes(pg_result($resaco,0,'pc01_veiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,855,11708,'','".AddSlashes(pg_result($resaco,0,'pc01_fraciona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,855,11727,'','".AddSlashes(pg_result($resaco,0,'pc01_validademinima'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,855,11728,'','".AddSlashes(pg_result($resaco,0,'pc01_obrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,855,17390,'','".AddSlashes(pg_result($resaco,0,'pc01_liberaresumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc01_codmater=null) { 
      $this->atualizacampos();
     $sql = " update pcmater set ";
     $virgula = "";
     if(trim($this->pc01_codmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_codmater"])){ 
       $sql  .= $virgula." pc01_codmater = $this->pc01_codmater ";
       $virgula = ",";
       if(trim($this->pc01_codmater) == null ){ 
         $this->erro_sql = " Campo Código do Material nao Informado.";
         $this->erro_campo = "pc01_codmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc01_descrmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_descrmater"])){ 
       $sql  .= $virgula." pc01_descrmater = '$this->pc01_descrmater' ";
       $virgula = ",";
       if(trim($this->pc01_descrmater) == null ){ 
         $this->erro_sql = " Campo Descrição do Material nao Informado.";
         $this->erro_campo = "pc01_descrmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc01_complmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_complmater"])){ 
       $sql  .= $virgula." pc01_complmater = '$this->pc01_complmater' ";
       $virgula = ",";
     }
     if(trim($this->pc01_codsubgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_codsubgrupo"])){ 
       $sql  .= $virgula." pc01_codsubgrupo = $this->pc01_codsubgrupo ";
       $virgula = ",";
       if(trim($this->pc01_codsubgrupo) == null ){ 
         $this->erro_sql = " Campo Código do Sub-Grupo nao Informado.";
         $this->erro_campo = "pc01_codsubgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc01_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_ativo"])){ 
       $sql  .= $virgula." pc01_ativo = '$this->pc01_ativo' ";
       $virgula = ",";
       if(trim($this->pc01_ativo) == null ){ 
         $this->erro_sql = " Campo Material inativo nao Informado.";
         $this->erro_campo = "pc01_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc01_conversao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_conversao"])){ 
       $sql  .= $virgula." pc01_conversao = '$this->pc01_conversao' ";
       $virgula = ",";
       if(trim($this->pc01_conversao) == null ){ 
         $this->erro_sql = " Campo Conversão nao Informado.";
         $this->erro_campo = "pc01_conversao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc01_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_id_usuario"])){ 
       $sql  .= $virgula." pc01_id_usuario = $this->pc01_id_usuario ";
       $virgula = ",";
       if(trim($this->pc01_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "pc01_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc01_libaut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_libaut"])){ 
       $sql  .= $virgula." pc01_libaut = '$this->pc01_libaut' ";
       $virgula = ",";
       if(trim($this->pc01_libaut) == null ){ 
         $this->erro_sql = " Campo Liberado para Autorização de Empenho nao Informado.";
         $this->erro_campo = "pc01_libaut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc01_servico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_servico"])){ 
       $sql  .= $virgula." pc01_servico = '$this->pc01_servico' ";
       $virgula = ",";
       if(trim($this->pc01_servico) == null ){ 
         $this->erro_sql = " Campo Serviço nao Informado.";
         $this->erro_campo = "pc01_servico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc01_veiculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_veiculo"])){ 
       $sql  .= $virgula." pc01_veiculo = '$this->pc01_veiculo' ";
       $virgula = ",";
       if(trim($this->pc01_veiculo) == null ){ 
         $this->erro_sql = " Campo Veículo nao Informado.";
         $this->erro_campo = "pc01_veiculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc01_fraciona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_fraciona"])){ 
       $sql  .= $virgula." pc01_fraciona = '$this->pc01_fraciona' ";
       $virgula = ",";
       if(trim($this->pc01_fraciona) == null ){ 
         $this->erro_sql = " Campo Fraciona nao Informado.";
         $this->erro_campo = "pc01_fraciona";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc01_validademinima)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_validademinima"])){ 
       $sql  .= $virgula." pc01_validademinima = '$this->pc01_validademinima' ";
       $virgula = ",";
       if(trim($this->pc01_validademinima) == null ){ 
         $this->erro_sql = " Campo Validade Mínima nao Informado.";
         $this->erro_campo = "pc01_validademinima";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc01_obrigatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_obrigatorio"])){ 
       $sql  .= $virgula." pc01_obrigatorio = '$this->pc01_obrigatorio' ";
       $virgula = ",";
       if(trim($this->pc01_obrigatorio) == null ){ 
         $this->erro_sql = " Campo Obrigatório nao Informado.";
         $this->erro_campo = "pc01_obrigatorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc01_liberaresumo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc01_liberaresumo"])){ 
       $sql  .= $virgula." pc01_liberaresumo = '$this->pc01_liberaresumo' ";
       $virgula = ",";
       if(trim($this->pc01_liberaresumo) == null ){ 
         $this->erro_sql = " Campo Liberar Resumo nao Informado.";
         $this->erro_campo = "pc01_liberaresumo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc01_codmater!=null){
       $sql .= " pc01_codmater = $this->pc01_codmater";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc01_codmater));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5491,'$this->pc01_codmater','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_codmater"]) || $this->pc01_codmater != "")
           $resac = db_query("insert into db_acount values($acount,855,5491,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_codmater'))."','$this->pc01_codmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_descrmater"]) || $this->pc01_descrmater != "")
           $resac = db_query("insert into db_acount values($acount,855,5492,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_descrmater'))."','$this->pc01_descrmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_complmater"]) || $this->pc01_complmater != "")
           $resac = db_query("insert into db_acount values($acount,855,5493,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_complmater'))."','$this->pc01_complmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_codsubgrupo"]) || $this->pc01_codsubgrupo != "")
           $resac = db_query("insert into db_acount values($acount,855,5494,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_codsubgrupo'))."','$this->pc01_codsubgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_ativo"]) || $this->pc01_ativo != "")
           $resac = db_query("insert into db_acount values($acount,855,6602,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_ativo'))."','$this->pc01_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_conversao"]) || $this->pc01_conversao != "")
           $resac = db_query("insert into db_acount values($acount,855,6761,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_conversao'))."','$this->pc01_conversao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_id_usuario"]) || $this->pc01_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,855,7425,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_id_usuario'))."','$this->pc01_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_libaut"]) || $this->pc01_libaut != "")
           $resac = db_query("insert into db_acount values($acount,855,8068,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_libaut'))."','$this->pc01_libaut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_servico"]) || $this->pc01_servico != "")
           $resac = db_query("insert into db_acount values($acount,855,10556,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_servico'))."','$this->pc01_servico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_veiculo"]) || $this->pc01_veiculo != "")
           $resac = db_query("insert into db_acount values($acount,855,11473,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_veiculo'))."','$this->pc01_veiculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_fraciona"]) || $this->pc01_fraciona != "")
           $resac = db_query("insert into db_acount values($acount,855,11708,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_fraciona'))."','$this->pc01_fraciona',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_validademinima"]) || $this->pc01_validademinima != "")
           $resac = db_query("insert into db_acount values($acount,855,11727,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_validademinima'))."','$this->pc01_validademinima',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_obrigatorio"]) || $this->pc01_obrigatorio != "")
           $resac = db_query("insert into db_acount values($acount,855,11728,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_obrigatorio'))."','$this->pc01_obrigatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc01_liberaresumo"]) || $this->pc01_liberaresumo != "")
           $resac = db_query("insert into db_acount values($acount,855,17390,'".AddSlashes(pg_result($resaco,$conresaco,'pc01_liberaresumo'))."','$this->pc01_liberaresumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Materiais nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc01_codmater;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Materiais nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc01_codmater;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc01_codmater;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc01_codmater=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc01_codmater));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5491,'$pc01_codmater','E')");
         $resac = db_query("insert into db_acount values($acount,855,5491,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_codmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,855,5492,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_descrmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,855,5493,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_complmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,855,5494,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_codsubgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,855,6602,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,855,6761,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_conversao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,855,7425,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,855,8068,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_libaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,855,10556,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,855,11473,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_veiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,855,11708,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_fraciona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,855,11727,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_validademinima'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,855,11728,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_obrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,855,17390,'','".AddSlashes(pg_result($resaco,$iresaco,'pc01_liberaresumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcmater
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc01_codmater != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc01_codmater = $pc01_codmater ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Materiais nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc01_codmater;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Materiais nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc01_codmater;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc01_codmater;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcmater";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc01_codmater=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcmater ";
     $sql .= "      left join db_usuarios  on  db_usuarios.id_usuario = pcmater.pc01_id_usuario";
     $sql .= "      inner join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      inner join pcgrupo  on  pcgrupo.pc03_codgrupo = pcsubgrupo.pc04_codgrupo";
     $sql .= "      inner join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc01_codmater!=null ){
         $sql2 .= " where pcmater.pc01_codmater = $pc01_codmater "; 
       }
       
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2 == ""?" where pc01_conversao is false ":" and pc01_conversao is false "); 
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
   function sql_query_desdobra ( $pc01_codmater=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcmater ";
     $sql .= "      inner join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      inner join pcgrupo  on  pcgrupo.pc03_codgrupo = pcsubgrupo.pc04_codgrupo";
     $sql .= "      inner join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql .= "      inner join pcmaterele  on  pcmaterele.pc07_codmater = pcmater.pc01_codmater";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele  = pcmaterele.pc07_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      left  join db_usuarios on  db_usuarios.id_usuario = pcmater.pc01_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($pc01_codmater!=null ){
         $sql2 .= " where pcmater.pc01_codmater = $pc01_codmater "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2 == ""?" where pc01_conversao is false ":" and pc01_conversao is false ");
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
   function sql_query_elemento ( $pc01_codmater=null,$campos="*",$ordem=null,$dbwhere=""){
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
    
     $sql .= " from pcmater ";
     $sql .= "      inner join pcmaterele   on  pcmaterele.pc07_codmater = pcmater.pc01_codmater  ";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele  = pcmaterele.pc07_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql2 = "";
     if($dbwhere==""){
       if($pc01_codmater!=null ){
         $sql2 .= " where pcmater.pc01_codmater = $pc01_codmater ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2 == ""?" where pc01_conversao is false ":" and pc01_conversao is false ");
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
   function sql_query_elementoautoriza ( $pc01_codmater=null,$campos="*",$ordem=null,$dbwhere=""){
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
    
     $sql .= " from pcmater ";
     $sql .= "      inner join pcmaterele   on  pcmaterele.pc07_codmater = pcmater.pc01_codmater ";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele  = pcmaterele.pc07_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join empautitem   on  empautitem.e55_item = pcmater.pc01_codmater 
                                           and  empautitem.e55_codele = orcelemento.o56_codele";
     $sql2 = "";
     if($dbwhere==""){
       if($pc01_codmater!=null ){
         $sql2 .= " where pcmater.pc01_codmater = $pc01_codmater ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2 == ""?" where pc01_conversao is false ":" and pc01_conversao is false ");
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
   function sql_query_file ( $pc01_codmater=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcmater ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc01_codmater!=null ){
         $sql2 .= " where pcmater.pc01_codmater = $pc01_codmater "; 
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
   function sql_query_grupo ( $pc01_codmater=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcmater ";
     $sql .= "      inner join pcmaterele   on  pcmaterele.pc07_codmater = pcmater.pc01_codmater  ";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele  = pcmaterele.pc07_codele    ";
     $sql .= "      inner join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      inner join pcgrupo  on  pcgrupo.pc03_codgrupo = pcsubgrupo.pc04_codgrupo";
     $sql .= "      inner join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc01_codmater!=null ){
         $sql2 .= " where pcmater.pc01_codmater = $pc01_codmater ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2 == ""?" where pc01_conversao is false ":" and pc01_conversao is false ");
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
  
 function sql_query_desdobraregistropreco( $pc01_codmater=null,$campos="*",$ordem=null,$dbwhere="") {
     
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
     $sql .= " from pcmater ";
     $sql .= "      inner join pcmaterele   on  pcmaterele.pc07_codmater = pcmater.pc01_codmater  ";
     $sql .= "      inner join solicitempcmater on pc16_codmater = pc01_codmater";
     $sql .= "      inner join solicitem        on pc16_solicitem = pc11_codigo";
     $sql .= "      inner join pcprocitem       on pc81_solicitem = pc11_codigo";
     $sql .= "      inner join liclicitem       on pc81_codprocitem = l21_codpcprocitem";
     $sql .= "      inner join pcorcamitemlic   on l21_codigo = pc26_liclicitem";
     $sql .= "      inner join pcorcamjulg      on pc26_orcamitem = pc24_orcamitem and pc24_pontuacao = 1";
     $sql .= "      left  join orcelemento      on  orcelemento.o56_codele  = pc07_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join solicitemregistropreco   on pc57_solicitem = pc11_codigo";
     $sql .= "      inner join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      inner join pcgrupo  on  pcgrupo.pc03_codgrupo = pcsubgrupo.pc04_codgrupo";
     $sql .= "      inner join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql .= "      left  join db_usuarios on  db_usuarios.id_usuario = pcmater.pc01_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($pc01_codmater!=null ){
         $sql2 .= " where pcmater.pc01_codmater = $pc01_codmater "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2 == ""?" where pc01_conversao is false ":" and pc01_conversao is false ");
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