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

//MODULO: protocolo
//CLASSE DA ENTIDADE protparam
class cl_protparam { 
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
   var $p90_emiterecib = 'f'; 
   var $p90_alteracgmprot = 'f'; 
   var $p90_valcpfcnpj = 'f'; 
   var $p90_impusuproc = 'f'; 
   var $p90_debiaber = 'f'; 
   var $p90_taxagrupo = 0; 
   var $p90_histpadcert = null; 
   var $p90_despachoob = 'f'; 
   var $p90_minchardesp = 0; 
   var $p90_andatual = 'f'; 
   var $p90_traminic = 0; 
   var $p90_modelcapaproc = 0; 
   var $p90_imprimevar = 'f'; 
   var $p90_instit = 0; 
   var $p90_impdepto = 'f'; 
   var $p90_db_documentotemplate = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p90_emiterecib = bool = Emite recibo na inclusão do processo 
                 p90_alteracgmprot = bool = Obriga alterar CGM durante a inclusão de processo 
                 p90_valcpfcnpj = bool = Valida CPF/CNPJ na inclusão de Processo 
                 p90_impusuproc = bool = Imprime o usuário na capa do processo 
                 p90_debiaber = bool = Verifica se o contribuinte tem debitos em aberto 
                 p90_taxagrupo = int4 = Código do grupo 
                 p90_histpadcert = text = Historico padrão para certidões 
                 p90_despachoob = bool = Despacho Obrigatório 
                 p90_minchardesp = int4 = Minímo de Caracteres p/ o despacho 
                 p90_andatual = bool = Mostra andamento atual 
                 p90_traminic = int4 = Trâmite 
                 p90_modelcapaproc = int4 = Modelo da Capa do Processo 
                 p90_imprimevar = bool = Impressão de variáveis 
                 p90_instit = int4 = Cod. Instituição 
                 p90_impdepto = bool = Imprime Departamento 
                 p90_db_documentotemplate = int4 = Documento Template 
                 ";
   //funcao construtor da classe 
   function cl_protparam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("protparam"); 
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
       $this->p90_emiterecib = ($this->p90_emiterecib == "f"?@$GLOBALS["HTTP_POST_VARS"]["p90_emiterecib"]:$this->p90_emiterecib);
       $this->p90_alteracgmprot = ($this->p90_alteracgmprot == "f"?@$GLOBALS["HTTP_POST_VARS"]["p90_alteracgmprot"]:$this->p90_alteracgmprot);
       $this->p90_valcpfcnpj = ($this->p90_valcpfcnpj == "f"?@$GLOBALS["HTTP_POST_VARS"]["p90_valcpfcnpj"]:$this->p90_valcpfcnpj);
       $this->p90_impusuproc = ($this->p90_impusuproc == "f"?@$GLOBALS["HTTP_POST_VARS"]["p90_impusuproc"]:$this->p90_impusuproc);
       $this->p90_debiaber = ($this->p90_debiaber == "f"?@$GLOBALS["HTTP_POST_VARS"]["p90_debiaber"]:$this->p90_debiaber);
       $this->p90_taxagrupo = ($this->p90_taxagrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["p90_taxagrupo"]:$this->p90_taxagrupo);
       $this->p90_histpadcert = ($this->p90_histpadcert == ""?@$GLOBALS["HTTP_POST_VARS"]["p90_histpadcert"]:$this->p90_histpadcert);
       $this->p90_despachoob = ($this->p90_despachoob == "f"?@$GLOBALS["HTTP_POST_VARS"]["p90_despachoob"]:$this->p90_despachoob);
       $this->p90_minchardesp = ($this->p90_minchardesp == ""?@$GLOBALS["HTTP_POST_VARS"]["p90_minchardesp"]:$this->p90_minchardesp);
       $this->p90_andatual = ($this->p90_andatual == "f"?@$GLOBALS["HTTP_POST_VARS"]["p90_andatual"]:$this->p90_andatual);
       $this->p90_traminic = ($this->p90_traminic == ""?@$GLOBALS["HTTP_POST_VARS"]["p90_traminic"]:$this->p90_traminic);
       $this->p90_modelcapaproc = ($this->p90_modelcapaproc == ""?@$GLOBALS["HTTP_POST_VARS"]["p90_modelcapaproc"]:$this->p90_modelcapaproc);
       $this->p90_imprimevar = ($this->p90_imprimevar == "f"?@$GLOBALS["HTTP_POST_VARS"]["p90_imprimevar"]:$this->p90_imprimevar);
       $this->p90_instit = ($this->p90_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["p90_instit"]:$this->p90_instit);
       $this->p90_impdepto = ($this->p90_impdepto == "f"?@$GLOBALS["HTTP_POST_VARS"]["p90_impdepto"]:$this->p90_impdepto);
       $this->p90_db_documentotemplate = ($this->p90_db_documentotemplate == ""?@$GLOBALS["HTTP_POST_VARS"]["p90_db_documentotemplate"]:$this->p90_db_documentotemplate);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->p90_emiterecib == null ){ 
       $this->erro_sql = " Campo Emite recibo na inclusão do processo nao Informado.";
       $this->erro_campo = "p90_emiterecib";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p90_alteracgmprot == null ){ 
       $this->erro_sql = " Campo Obriga alterar CGM durante a inclusão de processo nao Informado.";
       $this->erro_campo = "p90_alteracgmprot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p90_valcpfcnpj == null ){ 
       $this->erro_sql = " Campo Valida CPF/CNPJ na inclusão de Processo nao Informado.";
       $this->erro_campo = "p90_valcpfcnpj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p90_impusuproc == null ){ 
       $this->erro_sql = " Campo Imprime o usuário na capa do processo nao Informado.";
       $this->erro_campo = "p90_impusuproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p90_debiaber == null ){ 
       $this->erro_sql = " Campo Verifica se o contribuinte tem debitos em aberto nao Informado.";
       $this->erro_campo = "p90_debiaber";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p90_taxagrupo == null ){ 
       $this->erro_sql = " Campo Código do grupo nao Informado.";
       $this->erro_campo = "p90_taxagrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p90_histpadcert == null ){ 
       $this->erro_sql = " Campo Historico padrão para certidões nao Informado.";
       $this->erro_campo = "p90_histpadcert";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p90_despachoob == null ){ 
       $this->erro_sql = " Campo Despacho Obrigatório nao Informado.";
       $this->erro_campo = "p90_despachoob";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p90_minchardesp == null ){ 
       $this->p90_minchardesp = "0";
     }
     if($this->p90_andatual == null ){ 
       $this->erro_sql = " Campo Mostra andamento atual nao Informado.";
       $this->erro_campo = "p90_andatual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p90_traminic == null ){ 
       $this->erro_sql = " Campo Trâmite nao Informado.";
       $this->erro_campo = "p90_traminic";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p90_modelcapaproc == null ){ 
       $this->erro_sql = " Campo Modelo da Capa do Processo nao Informado.";
       $this->erro_campo = "p90_modelcapaproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p90_imprimevar == null ){ 
       $this->erro_sql = " Campo Impressão de variáveis nao Informado.";
       $this->erro_campo = "p90_imprimevar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p90_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "p90_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p90_impdepto == null ){ 
       $this->erro_sql = " Campo Imprime Departamento nao Informado.";
       $this->erro_campo = "p90_impdepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p90_db_documentotemplate == null ){ 
       $this->p90_db_documentotemplate = "0";
     }
     $sql = "insert into protparam(
                                       p90_emiterecib 
                                      ,p90_alteracgmprot 
                                      ,p90_valcpfcnpj 
                                      ,p90_impusuproc 
                                      ,p90_debiaber 
                                      ,p90_taxagrupo 
                                      ,p90_histpadcert 
                                      ,p90_despachoob 
                                      ,p90_minchardesp 
                                      ,p90_andatual 
                                      ,p90_traminic 
                                      ,p90_modelcapaproc 
                                      ,p90_imprimevar 
                                      ,p90_instit 
                                      ,p90_impdepto 
                                      ,p90_db_documentotemplate 
                       )
                values (
                                '$this->p90_emiterecib' 
                               ,'$this->p90_alteracgmprot' 
                               ,'$this->p90_valcpfcnpj' 
                               ,'$this->p90_impusuproc' 
                               ,'$this->p90_debiaber' 
                               ,$this->p90_taxagrupo 
                               ,'$this->p90_histpadcert' 
                               ,'$this->p90_despachoob' 
                               ,$this->p90_minchardesp 
                               ,'$this->p90_andatual' 
                               ,$this->p90_traminic 
                               ,$this->p90_modelcapaproc 
                               ,'$this->p90_imprimevar' 
                               ,$this->p90_instit 
                               ,'$this->p90_impdepto' 
                               ,$this->p90_db_documentotemplate 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Manutenção de Parametros do Protocolo () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Manutenção de Parametros do Protocolo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Manutenção de Parametros do Protocolo () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update protparam set ";
     $virgula = "";
     if(trim($this->p90_emiterecib)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_emiterecib"])){ 
       $sql  .= $virgula." p90_emiterecib = '$this->p90_emiterecib' ";
       $virgula = ",";
       if(trim($this->p90_emiterecib) == null ){ 
         $this->erro_sql = " Campo Emite recibo na inclusão do processo nao Informado.";
         $this->erro_campo = "p90_emiterecib";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_alteracgmprot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_alteracgmprot"])){ 
       $sql  .= $virgula." p90_alteracgmprot = '$this->p90_alteracgmprot' ";
       $virgula = ",";
       if(trim($this->p90_alteracgmprot) == null ){ 
         $this->erro_sql = " Campo Obriga alterar CGM durante a inclusão de processo nao Informado.";
         $this->erro_campo = "p90_alteracgmprot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_valcpfcnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_valcpfcnpj"])){ 
       $sql  .= $virgula." p90_valcpfcnpj = '$this->p90_valcpfcnpj' ";
       $virgula = ",";
       if(trim($this->p90_valcpfcnpj) == null ){ 
         $this->erro_sql = " Campo Valida CPF/CNPJ na inclusão de Processo nao Informado.";
         $this->erro_campo = "p90_valcpfcnpj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_impusuproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_impusuproc"])){ 
       $sql  .= $virgula." p90_impusuproc = '$this->p90_impusuproc' ";
       $virgula = ",";
       if(trim($this->p90_impusuproc) == null ){ 
         $this->erro_sql = " Campo Imprime o usuário na capa do processo nao Informado.";
         $this->erro_campo = "p90_impusuproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_debiaber)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_debiaber"])){ 
       $sql  .= $virgula." p90_debiaber = '$this->p90_debiaber' ";
       $virgula = ",";
       if(trim($this->p90_debiaber) == null ){ 
         $this->erro_sql = " Campo Verifica se o contribuinte tem debitos em aberto nao Informado.";
         $this->erro_campo = "p90_debiaber";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_taxagrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_taxagrupo"])){ 
       $sql  .= $virgula." p90_taxagrupo = $this->p90_taxagrupo ";
       $virgula = ",";
       if(trim($this->p90_taxagrupo) == null ){ 
         $this->erro_sql = " Campo Código do grupo nao Informado.";
         $this->erro_campo = "p90_taxagrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_histpadcert)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_histpadcert"])){ 
       $sql  .= $virgula." p90_histpadcert = '$this->p90_histpadcert' ";
       $virgula = ",";
       if(trim($this->p90_histpadcert) == null ){ 
         $this->erro_sql = " Campo Historico padrão para certidões nao Informado.";
         $this->erro_campo = "p90_histpadcert";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_despachoob)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_despachoob"])){ 
       $sql  .= $virgula." p90_despachoob = '$this->p90_despachoob' ";
       $virgula = ",";
       if(trim($this->p90_despachoob) == null ){ 
         $this->erro_sql = " Campo Despacho Obrigatório nao Informado.";
         $this->erro_campo = "p90_despachoob";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_minchardesp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_minchardesp"])){ 
        if(trim($this->p90_minchardesp)=="" && isset($GLOBALS["HTTP_POST_VARS"]["p90_minchardesp"])){ 
           $this->p90_minchardesp = "0" ; 
        } 
       $sql  .= $virgula." p90_minchardesp = $this->p90_minchardesp ";
       $virgula = ",";
     }
     if(trim($this->p90_andatual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_andatual"])){ 
       $sql  .= $virgula." p90_andatual = '$this->p90_andatual' ";
       $virgula = ",";
       if(trim($this->p90_andatual) == null ){ 
         $this->erro_sql = " Campo Mostra andamento atual nao Informado.";
         $this->erro_campo = "p90_andatual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_traminic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_traminic"])){ 
       $sql  .= $virgula." p90_traminic = $this->p90_traminic ";
       $virgula = ",";
       if(trim($this->p90_traminic) == null ){ 
         $this->erro_sql = " Campo Trâmite nao Informado.";
         $this->erro_campo = "p90_traminic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_modelcapaproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_modelcapaproc"])){ 
       $sql  .= $virgula." p90_modelcapaproc = $this->p90_modelcapaproc ";
       $virgula = ",";
       if(trim($this->p90_modelcapaproc) == null ){ 
         $this->erro_sql = " Campo Modelo da Capa do Processo nao Informado.";
         $this->erro_campo = "p90_modelcapaproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_imprimevar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_imprimevar"])){ 
       $sql  .= $virgula." p90_imprimevar = '$this->p90_imprimevar' ";
       $virgula = ",";
       if(trim($this->p90_imprimevar) == null ){ 
         $this->erro_sql = " Campo Impressão de variáveis nao Informado.";
         $this->erro_campo = "p90_imprimevar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_instit"])){ 
       $sql  .= $virgula." p90_instit = $this->p90_instit ";
       $virgula = ",";
       if(trim($this->p90_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "p90_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_impdepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_impdepto"])){ 
       $sql  .= $virgula." p90_impdepto = '$this->p90_impdepto' ";
       $virgula = ",";
       if(trim($this->p90_impdepto) == null ){ 
         $this->erro_sql = " Campo Imprime Departamento nao Informado.";
         $this->erro_campo = "p90_impdepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_db_documentotemplate)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_db_documentotemplate"])){ 
        if(trim($this->p90_db_documentotemplate)=="" && isset($GLOBALS["HTTP_POST_VARS"]["p90_db_documentotemplate"])){ 
           $this->p90_db_documentotemplate = "0" ; 
        } 
       $sql  .= $virgula." p90_db_documentotemplate = $this->p90_db_documentotemplate ";
       $virgula = ",";
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Manutenção de Parametros do Protocolo nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Manutenção de Parametros do Protocolo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from protparam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Manutenção de Parametros do Protocolo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Manutenção de Parametros do Protocolo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:protparam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $oid = null,$campos="protparam.oid,*",$ordem=null,$dbwhere=""){ 
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
       $sql .= " from protparam ";
       $sql .= "      inner join db_config  on  db_config.codigo = protparam.p90_instit";
       $sql .= "      inner join taxagrupo  on  taxagrupo.k06_taxagrupo = protparam.p90_taxagrupo";
       $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
       $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
       $sql .= "       left join db_documentotemplate  on  db_documentotemplate.db82_sequencial = protparam.p90_db_documentotemplate";
       $sql2 = "";
       if($dbwhere==""){
         if( $oid != "" && $oid != null){
            $sql2 = " where protparam.oid = '$oid'";
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
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from protparam ";
     $sql2 = "";
     if($dbwhere==""){
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
   function alterar_instit ($instit) { 
      $this->atualizacampos();
     $sql = " update protparam set ";
     $virgula = "";
     if(trim($this->p90_emiterecib)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_emiterecib"])){ 
       $sql  .= $virgula." p90_emiterecib = '$this->p90_emiterecib' ";
       $virgula = ",";
       if(trim($this->p90_emiterecib) == null ){ 
         $this->erro_sql = " Campo Emite recibo na inclusão do processo nao Informado.";
         $this->erro_campo = "p90_emiterecib";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_alteracgmprot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_alteracgmprot"])){ 
       $sql  .= $virgula." p90_alteracgmprot = '$this->p90_alteracgmprot' ";
       $virgula = ",";
       if(trim($this->p90_alteracgmprot) == null ){ 
         $this->erro_sql = " Campo Obriga alterar CGM durante a inclusão de processo nao Informado.";
         $this->erro_campo = "p90_alteracgmprot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_valcpfcnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_valcpfcnpj"])){ 
       $sql  .= $virgula." p90_valcpfcnpj = '$this->p90_valcpfcnpj' ";
       $virgula = ",";
       if(trim($this->p90_valcpfcnpj) == null ){ 
         $this->erro_sql = " Campo Valida CPF/CNPJ na inclusão de Processo nao Informado.";
         $this->erro_campo = "p90_valcpfcnpj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_impusuproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_impusuproc"])){ 
       $sql  .= $virgula." p90_impusuproc = '$this->p90_impusuproc' ";
       $virgula = ",";
       if(trim($this->p90_impusuproc) == null ){ 
         $this->erro_sql = " Campo Imprime o usuário na capa do processo nao Informado.";
         $this->erro_campo = "p90_impusuproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_debiaber)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_debiaber"])){ 
       $sql  .= $virgula." p90_debiaber = '$this->p90_debiaber' ";
       $virgula = ",";
       if(trim($this->p90_debiaber) == null ){ 
         $this->erro_sql = " Campo Verifica se o contribuinte tem debitos em aberto nao Informado.";
         $this->erro_campo = "p90_debiaber";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_taxagrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_taxagrupo"])){ 
       $sql  .= $virgula." p90_taxagrupo = $this->p90_taxagrupo ";
       $virgula = ",";
       if(trim($this->p90_taxagrupo) == null ){ 
         $this->erro_sql = " Campo Código do grupo nao Informado.";
         $this->erro_campo = "p90_taxagrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_histpadcert)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_histpadcert"])){ 
       $sql  .= $virgula." p90_histpadcert = '$this->p90_histpadcert' ";
       $virgula = ",";
       if(trim($this->p90_histpadcert) == null ){ 
         $this->erro_sql = " Campo Historico padrão para certidões nao Informado.";
         $this->erro_campo = "p90_histpadcert";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_despachoob)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_despachoob"])){ 
       $sql  .= $virgula." p90_despachoob = '$this->p90_despachoob' ";
       $virgula = ",";
       if(trim($this->p90_despachoob) == null ){ 
         $this->erro_sql = " Campo Despacho Obrigatório nao Informado.";
         $this->erro_campo = "p90_despachoob";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_minchardesp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_minchardesp"])){ 
        if(trim($this->p90_minchardesp)=="" && isset($GLOBALS["HTTP_POST_VARS"]["p90_minchardesp"])){ 
           $this->p90_minchardesp = "0" ; 
        } 
       $sql  .= $virgula." p90_minchardesp = $this->p90_minchardesp ";
       $virgula = ",";
     }
     if(trim($this->p90_andatual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_andatual"])){ 
       $sql  .= $virgula." p90_andatual = '$this->p90_andatual' ";
       $virgula = ",";
       if(trim($this->p90_andatual) == null ){ 
         $this->erro_sql = " Campo Mostra andamento atual nao Informado.";
         $this->erro_campo = "p90_andatual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_traminic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_traminic"])){ 
       $sql  .= $virgula." p90_traminic = $this->p90_traminic ";
       $virgula = ",";
       if(trim($this->p90_traminic) == null ){ 
         $this->erro_sql = " Campo Trâmite nao Informado.";
         $this->erro_campo = "p90_traminic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_modelcapaproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_modelcapaproc"])){ 
       $sql  .= $virgula." p90_modelcapaproc = $this->p90_modelcapaproc ";
       $virgula = ",";
       if(trim($this->p90_modelcapaproc) == null ){ 
         $this->erro_sql = " Campo Modelo da Capa do Processo nao Informado.";
         $this->erro_campo = "p90_modelcapaproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_imprimevar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_imprimevar"])){ 
       $sql  .= $virgula." p90_imprimevar = '$this->p90_imprimevar' ";
       $virgula = ",";
       if(trim($this->p90_imprimevar) == null ){ 
         $this->erro_sql = " Campo Impressão de variáveis nao Informado.";
         $this->erro_campo = "p90_imprimevar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_instit"])){ 
       $sql  .= $virgula." p90_instit = $this->p90_instit ";
       $virgula = ",";
       if(trim($this->p90_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "p90_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_impdepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p90_impdepto"])){ 
       $sql  .= $virgula." p90_impdepto = '$this->p90_impdepto' ";
       $virgula = ",";
       if(trim($this->p90_impdepto) == null ){ 
         $this->erro_sql = " Campo Imprime Departamento nao Informado.";
         $this->erro_campo = "p90_impdepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p90_db_documentotemplate)!="" ){
       
       $sql  .= $virgula." p90_db_documentotemplate = '$this->p90_db_documentotemplate' ";
       $virgula = ",";
     } else {
       
       $sql  .= $virgula." p90_db_documentotemplate = null";
       $virgula = ",";
     } 
     
     $sql .= " where ";
     
     if($instit != null){
      $sql .= " p90_instit = $instit ";	
     }else{
      $sql .= "p90_instit = $this->p90_instit";
     }
          
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Manutenção de Parametros do Protocolo nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Manutenção de Parametros do Protocolo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   }
   
   /**
    * 
    * Busca os parâmetros dos documentos
    * @param integer $oid
    * @param String $campos
    * @param String $ordem
    * @param String $dbwhere
    * @return string
    */
   function sql_query_documentos ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from protparam ";
     $sql .= " left join db_documentotemplate on db_documentotemplate.db82_sequencial = protparam.p90_db_documentotemplate ";
     $sql2 = "";
     if($dbwhere==""){
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