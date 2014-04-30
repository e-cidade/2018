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

//MODULO: prefeitura
//CLASSE DA ENTIDADE db_daesocios
class cl_db_daesocios { 
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
   var $w06_codigo = 0; 
   var $w06_socio = 0; 
   var $w06_cgccpf = null; 
   var $w06_rg = 0; 
   var $w06_nome = null; 
   var $w06_ender = null; 
   var $w06_numero = 0; 
   var $w06_compl = null; 
   var $w06_bairro = null; 
   var $w06_cep = 0; 
   var $w06_uf = null; 
   var $w06_percent = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 w06_codigo = int4 = Cóidgo 
                 w06_socio = int4 = Socios 
                 w06_cgccpf = varchar(14) = CGC-CNPJ 
                 w06_rg = int8 = Identidade 
                 w06_nome = varchar(40) = Nome 
                 w06_ender = varchar(40) = Endereço 
                 w06_numero = int4 = Número 
                 w06_compl = varchar(15) = Complemento 
                 w06_bairro = varchar(40) = Bairro 
                 w06_cep = int4 = CEP 
                 w06_uf = varchar(2) = UF 
                 w06_percent = int4 = Percentual de sociedade 
                 ";
   //funcao construtor da classe 
   function cl_db_daesocios() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_daesocios"); 
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
       $this->w06_codigo = ($this->w06_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["w06_codigo"]:$this->w06_codigo);
       $this->w06_socio = ($this->w06_socio == ""?@$GLOBALS["HTTP_POST_VARS"]["w06_socio"]:$this->w06_socio);
       $this->w06_cgccpf = ($this->w06_cgccpf == ""?@$GLOBALS["HTTP_POST_VARS"]["w06_cgccpf"]:$this->w06_cgccpf);
       $this->w06_rg = ($this->w06_rg == ""?@$GLOBALS["HTTP_POST_VARS"]["w06_rg"]:$this->w06_rg);
       $this->w06_nome = ($this->w06_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["w06_nome"]:$this->w06_nome);
       $this->w06_ender = ($this->w06_ender == ""?@$GLOBALS["HTTP_POST_VARS"]["w06_ender"]:$this->w06_ender);
       $this->w06_numero = ($this->w06_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["w06_numero"]:$this->w06_numero);
       $this->w06_compl = ($this->w06_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["w06_compl"]:$this->w06_compl);
       $this->w06_bairro = ($this->w06_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["w06_bairro"]:$this->w06_bairro);
       $this->w06_cep = ($this->w06_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["w06_cep"]:$this->w06_cep);
       $this->w06_uf = ($this->w06_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["w06_uf"]:$this->w06_uf);
       $this->w06_percent = ($this->w06_percent == ""?@$GLOBALS["HTTP_POST_VARS"]["w06_percent"]:$this->w06_percent);
     }else{
       $this->w06_codigo = ($this->w06_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["w06_codigo"]:$this->w06_codigo);
       $this->w06_socio = ($this->w06_socio == ""?@$GLOBALS["HTTP_POST_VARS"]["w06_socio"]:$this->w06_socio);
     }
   }
   // funcao para inclusao
   function incluir ($w06_codigo,$w06_socio){ 
      $this->atualizacampos();
     if($this->w06_cgccpf == null ){ 
       $this->erro_sql = " Campo CGC-CNPJ nao Informado.";
       $this->erro_campo = "w06_cgccpf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w06_rg == null ){ 
       $this->erro_sql = " Campo Identidade nao Informado.";
       $this->erro_campo = "w06_rg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w06_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "w06_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w06_ender == null ){ 
       $this->erro_sql = " Campo Endereço nao Informado.";
       $this->erro_campo = "w06_ender";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w06_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "w06_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w06_compl == null ){ 
       $this->erro_sql = " Campo Complemento nao Informado.";
       $this->erro_campo = "w06_compl";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w06_bairro == null ){ 
       $this->erro_sql = " Campo Bairro nao Informado.";
       $this->erro_campo = "w06_bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w06_cep == null ){ 
       $this->erro_sql = " Campo CEP nao Informado.";
       $this->erro_campo = "w06_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w06_uf == null ){ 
       $this->erro_sql = " Campo UF nao Informado.";
       $this->erro_campo = "w06_uf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w06_percent == null ){ 
       $this->erro_sql = " Campo Percentual de sociedade nao Informado.";
       $this->erro_campo = "w06_percent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->w06_codigo = $w06_codigo; 
       $this->w06_socio = $w06_socio; 
     if(($this->w06_codigo == null) || ($this->w06_codigo == "") ){ 
       $this->erro_sql = " Campo w06_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->w06_socio == null) || ($this->w06_socio == "") ){ 
       $this->erro_sql = " Campo w06_socio nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_daesocios(
                                       w06_codigo 
                                      ,w06_socio 
                                      ,w06_cgccpf 
                                      ,w06_rg 
                                      ,w06_nome 
                                      ,w06_ender 
                                      ,w06_numero 
                                      ,w06_compl 
                                      ,w06_bairro 
                                      ,w06_cep 
                                      ,w06_uf 
                                      ,w06_percent 
                       )
                values (
                                $this->w06_codigo 
                               ,$this->w06_socio 
                               ,'$this->w06_cgccpf' 
                               ,$this->w06_rg 
                               ,'$this->w06_nome' 
                               ,'$this->w06_ender' 
                               ,$this->w06_numero 
                               ,'$this->w06_compl' 
                               ,'$this->w06_bairro' 
                               ,$this->w06_cep 
                               ,'$this->w06_uf' 
                               ,$this->w06_percent 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tabela de socios do dae ($this->w06_codigo."-".$this->w06_socio) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tabela de socios do dae já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tabela de socios do dae ($this->w06_codigo."-".$this->w06_socio) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w06_codigo."-".$this->w06_socio;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->w06_codigo,$this->w06_socio));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4656,'$this->w06_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,4726,'$this->w06_socio','I')");
       $resac = db_query("insert into db_acount values($acount,609,4656,'','".AddSlashes(pg_result($resaco,0,'w06_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,609,4726,'','".AddSlashes(pg_result($resaco,0,'w06_socio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,609,4639,'','".AddSlashes(pg_result($resaco,0,'w06_cgccpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,609,4640,'','".AddSlashes(pg_result($resaco,0,'w06_rg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,609,4641,'','".AddSlashes(pg_result($resaco,0,'w06_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,609,4642,'','".AddSlashes(pg_result($resaco,0,'w06_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,609,4643,'','".AddSlashes(pg_result($resaco,0,'w06_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,609,4644,'','".AddSlashes(pg_result($resaco,0,'w06_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,609,4645,'','".AddSlashes(pg_result($resaco,0,'w06_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,609,4646,'','".AddSlashes(pg_result($resaco,0,'w06_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,609,4647,'','".AddSlashes(pg_result($resaco,0,'w06_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,609,4648,'','".AddSlashes(pg_result($resaco,0,'w06_percent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($w06_codigo=null,$w06_socio=null) { 
      $this->atualizacampos();
     $sql = " update db_daesocios set ";
     $virgula = "";
     if(trim($this->w06_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w06_codigo"])){ 
       $sql  .= $virgula." w06_codigo = $this->w06_codigo ";
       $virgula = ",";
       if(trim($this->w06_codigo) == null ){ 
         $this->erro_sql = " Campo Cóidgo nao Informado.";
         $this->erro_campo = "w06_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w06_socio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w06_socio"])){ 
       $sql  .= $virgula." w06_socio = $this->w06_socio ";
       $virgula = ",";
       if(trim($this->w06_socio) == null ){ 
         $this->erro_sql = " Campo Socios nao Informado.";
         $this->erro_campo = "w06_socio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w06_cgccpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w06_cgccpf"])){ 
       $sql  .= $virgula." w06_cgccpf = '$this->w06_cgccpf' ";
       $virgula = ",";
       if(trim($this->w06_cgccpf) == null ){ 
         $this->erro_sql = " Campo CGC-CNPJ nao Informado.";
         $this->erro_campo = "w06_cgccpf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w06_rg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w06_rg"])){ 
       $sql  .= $virgula." w06_rg = $this->w06_rg ";
       $virgula = ",";
       if(trim($this->w06_rg) == null ){ 
         $this->erro_sql = " Campo Identidade nao Informado.";
         $this->erro_campo = "w06_rg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w06_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w06_nome"])){ 
       $sql  .= $virgula." w06_nome = '$this->w06_nome' ";
       $virgula = ",";
       if(trim($this->w06_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "w06_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w06_ender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w06_ender"])){ 
       $sql  .= $virgula." w06_ender = '$this->w06_ender' ";
       $virgula = ",";
       if(trim($this->w06_ender) == null ){ 
         $this->erro_sql = " Campo Endereço nao Informado.";
         $this->erro_campo = "w06_ender";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w06_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w06_numero"])){ 
       $sql  .= $virgula." w06_numero = $this->w06_numero ";
       $virgula = ",";
       if(trim($this->w06_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "w06_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w06_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w06_compl"])){ 
       $sql  .= $virgula." w06_compl = '$this->w06_compl' ";
       $virgula = ",";
       if(trim($this->w06_compl) == null ){ 
         $this->erro_sql = " Campo Complemento nao Informado.";
         $this->erro_campo = "w06_compl";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w06_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w06_bairro"])){ 
       $sql  .= $virgula." w06_bairro = '$this->w06_bairro' ";
       $virgula = ",";
       if(trim($this->w06_bairro) == null ){ 
         $this->erro_sql = " Campo Bairro nao Informado.";
         $this->erro_campo = "w06_bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w06_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w06_cep"])){ 
       $sql  .= $virgula." w06_cep = $this->w06_cep ";
       $virgula = ",";
       if(trim($this->w06_cep) == null ){ 
         $this->erro_sql = " Campo CEP nao Informado.";
         $this->erro_campo = "w06_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w06_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w06_uf"])){ 
       $sql  .= $virgula." w06_uf = '$this->w06_uf' ";
       $virgula = ",";
       if(trim($this->w06_uf) == null ){ 
         $this->erro_sql = " Campo UF nao Informado.";
         $this->erro_campo = "w06_uf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w06_percent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w06_percent"])){ 
       $sql  .= $virgula." w06_percent = $this->w06_percent ";
       $virgula = ",";
       if(trim($this->w06_percent) == null ){ 
         $this->erro_sql = " Campo Percentual de sociedade nao Informado.";
         $this->erro_campo = "w06_percent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($w06_codigo!=null){
       $sql .= " w06_codigo = $this->w06_codigo";
     }
     if($w06_socio!=null){
       $sql .= " and  w06_socio = $this->w06_socio";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->w06_codigo,$this->w06_socio));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4656,'$this->w06_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,4726,'$this->w06_socio','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w06_codigo"]))
           $resac = db_query("insert into db_acount values($acount,609,4656,'".AddSlashes(pg_result($resaco,$conresaco,'w06_codigo'))."','$this->w06_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w06_socio"]))
           $resac = db_query("insert into db_acount values($acount,609,4726,'".AddSlashes(pg_result($resaco,$conresaco,'w06_socio'))."','$this->w06_socio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w06_cgccpf"]))
           $resac = db_query("insert into db_acount values($acount,609,4639,'".AddSlashes(pg_result($resaco,$conresaco,'w06_cgccpf'))."','$this->w06_cgccpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w06_rg"]))
           $resac = db_query("insert into db_acount values($acount,609,4640,'".AddSlashes(pg_result($resaco,$conresaco,'w06_rg'))."','$this->w06_rg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w06_nome"]))
           $resac = db_query("insert into db_acount values($acount,609,4641,'".AddSlashes(pg_result($resaco,$conresaco,'w06_nome'))."','$this->w06_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w06_ender"]))
           $resac = db_query("insert into db_acount values($acount,609,4642,'".AddSlashes(pg_result($resaco,$conresaco,'w06_ender'))."','$this->w06_ender',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w06_numero"]))
           $resac = db_query("insert into db_acount values($acount,609,4643,'".AddSlashes(pg_result($resaco,$conresaco,'w06_numero'))."','$this->w06_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w06_compl"]))
           $resac = db_query("insert into db_acount values($acount,609,4644,'".AddSlashes(pg_result($resaco,$conresaco,'w06_compl'))."','$this->w06_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w06_bairro"]))
           $resac = db_query("insert into db_acount values($acount,609,4645,'".AddSlashes(pg_result($resaco,$conresaco,'w06_bairro'))."','$this->w06_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w06_cep"]))
           $resac = db_query("insert into db_acount values($acount,609,4646,'".AddSlashes(pg_result($resaco,$conresaco,'w06_cep'))."','$this->w06_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w06_uf"]))
           $resac = db_query("insert into db_acount values($acount,609,4647,'".AddSlashes(pg_result($resaco,$conresaco,'w06_uf'))."','$this->w06_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w06_percent"]))
           $resac = db_query("insert into db_acount values($acount,609,4648,'".AddSlashes(pg_result($resaco,$conresaco,'w06_percent'))."','$this->w06_percent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tabela de socios do dae nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->w06_codigo."-".$this->w06_socio;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tabela de socios do dae nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->w06_codigo."-".$this->w06_socio;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w06_codigo."-".$this->w06_socio;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($w06_codigo=null,$w06_socio=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($w06_codigo,$w06_socio));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4656,'$w06_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,4726,'$w06_socio','E')");
         $resac = db_query("insert into db_acount values($acount,609,4656,'','".AddSlashes(pg_result($resaco,$iresaco,'w06_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,609,4726,'','".AddSlashes(pg_result($resaco,$iresaco,'w06_socio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,609,4639,'','".AddSlashes(pg_result($resaco,$iresaco,'w06_cgccpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,609,4640,'','".AddSlashes(pg_result($resaco,$iresaco,'w06_rg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,609,4641,'','".AddSlashes(pg_result($resaco,$iresaco,'w06_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,609,4642,'','".AddSlashes(pg_result($resaco,$iresaco,'w06_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,609,4643,'','".AddSlashes(pg_result($resaco,$iresaco,'w06_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,609,4644,'','".AddSlashes(pg_result($resaco,$iresaco,'w06_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,609,4645,'','".AddSlashes(pg_result($resaco,$iresaco,'w06_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,609,4646,'','".AddSlashes(pg_result($resaco,$iresaco,'w06_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,609,4647,'','".AddSlashes(pg_result($resaco,$iresaco,'w06_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,609,4648,'','".AddSlashes(pg_result($resaco,$iresaco,'w06_percent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_daesocios
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($w06_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w06_codigo = $w06_codigo ";
        }
        if($w06_socio != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w06_socio = $w06_socio ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tabela de socios do dae nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$w06_codigo."-".$w06_socio;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tabela de socios do dae nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$w06_codigo."-".$w06_socio;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$w06_codigo."-".$w06_socio;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_daesocios";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>