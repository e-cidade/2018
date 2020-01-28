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

//MODULO: ambulatorial
//CLASSE DA ENTIDADE sau_config
class cl_sau_config { 
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
   var $s103_c_lancafaa = null; 
   var $s103_v_msgagenda = null; 
   var $s103_c_agendaproc = null; 
   var $s103_c_emitircomprovante = null; 
   var $s103_i_departamentos = 0; 
   var $s103_c_emitirfaa = null; 
   var $s103_c_cancelafa = null; 
   var $s103_i_modalidade = 0; 
   var $s103_c_sgdb = null; 
   var $s103_c_ip = null; 
   var $s103_i_porta = 0; 
   var $s103_c_senha = null; 
   var $s103_c_usuario = null; 
   var $s103_c_apareceragenda = null; 
   var $s103_c_idadeproc = null; 
   var $s103_c_servicoproc = null; 
   var $s103_c_ipauto = 'f'; 
   var $s103_c_agendaprog = null; 
   var $s103_i_validaagenda = 0; 
   var $s103_i_revisacgs = 0; 
   var $s103_i_tipodb = 0; 
   var $s103_i_datahorafaa = 0; 
   var $s103_i_modelofaa = 0; 
   var $s103_c_bpasecrdestino = null; 
   var $s103_c_bpasigla = null; 
   var $s103_c_bpaibge = null; 
   var $s103_i_todacomp = 0; 
   var $s103_procsemcbo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s103_c_lancafaa = char(1) = Lan�amento FAA 
                 s103_v_msgagenda = varchar(200) = Mensagem Agenda 
                 s103_c_agendaproc = char(1) = Procedimento Agenda 
                 s103_c_emitircomprovante = char(1) = Emitir Comprovante 
                 s103_i_departamentos = int4 = Controle UPS 
                 s103_c_emitirfaa = char(1) = Gerar FA automatica 
                 s103_c_cancelafa = char(1) = Cancelar FA anteriores 
                 s103_i_modalidade = int8 = Modalidade 
                 s103_c_sgdb = char(150) = Nome do Banco 
                 s103_c_ip = char(15) = IP 
                 s103_i_porta = int4 = Porta 
                 s103_c_senha = char(150) = Senha 
                 s103_c_usuario = char(150) = Usuario 
                 s103_c_apareceragenda = char(1) = Aparecer FAAs geradas 
                 s103_c_idadeproc = char(1) = Valida Idade do Procedimento 
                 s103_c_servicoproc = char(1) = Valida Servi�o do Procedimento 
                 s103_c_ipauto = bool = IP Automatico 
                 s103_c_agendaprog = char(1) = A��o Program�tica na Agenda 
                 s103_i_validaagenda = int4 = Verificar de outras agendas(dias) 
                 s103_i_revisacgs = int4 = Revisar cadastro do CGS(dias) 
                 s103_i_tipodb = int4 = Tipo de Banco 
                 s103_i_datahorafaa = int4 = Data e hora na FAA 
                 s103_i_modelofaa = int4 = Modelo FA 
                 s103_c_bpasecrdestino = char(50) = Secretaria Destino 
                 s103_c_bpasigla = char(5) = Sigla 
                 s103_c_bpaibge = char(30) = Codigo do IBGE 
                 s103_i_todacomp = int4 = Apresentar Todas Compet�ncia 
                 s103_procsemcbo = char(1) = Exibir Procedimentos sem CBO 
                 ";
   //funcao construtor da classe 
   function cl_sau_config() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_config"); 
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
       $this->s103_c_lancafaa = ($this->s103_c_lancafaa == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_lancafaa"]:$this->s103_c_lancafaa);
       $this->s103_v_msgagenda = ($this->s103_v_msgagenda == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_v_msgagenda"]:$this->s103_v_msgagenda);
       $this->s103_c_agendaproc = ($this->s103_c_agendaproc == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_agendaproc"]:$this->s103_c_agendaproc);
       $this->s103_c_emitircomprovante = ($this->s103_c_emitircomprovante == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_emitircomprovante"]:$this->s103_c_emitircomprovante);
       $this->s103_i_departamentos = ($this->s103_i_departamentos == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_i_departamentos"]:$this->s103_i_departamentos);
       $this->s103_c_emitirfaa = ($this->s103_c_emitirfaa == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_emitirfaa"]:$this->s103_c_emitirfaa);
       $this->s103_c_cancelafa = ($this->s103_c_cancelafa == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_cancelafa"]:$this->s103_c_cancelafa);
       $this->s103_i_modalidade = ($this->s103_i_modalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_i_modalidade"]:$this->s103_i_modalidade);
       $this->s103_c_sgdb = ($this->s103_c_sgdb == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_sgdb"]:$this->s103_c_sgdb);
       $this->s103_c_ip = ($this->s103_c_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_ip"]:$this->s103_c_ip);
       $this->s103_i_porta = ($this->s103_i_porta == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_i_porta"]:$this->s103_i_porta);
       $this->s103_c_senha = ($this->s103_c_senha == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_senha"]:$this->s103_c_senha);
       $this->s103_c_usuario = ($this->s103_c_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_usuario"]:$this->s103_c_usuario);
       $this->s103_c_apareceragenda = ($this->s103_c_apareceragenda == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_apareceragenda"]:$this->s103_c_apareceragenda);
       $this->s103_c_idadeproc = ($this->s103_c_idadeproc == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_idadeproc"]:$this->s103_c_idadeproc);
       $this->s103_c_servicoproc = ($this->s103_c_servicoproc == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_servicoproc"]:$this->s103_c_servicoproc);
       $this->s103_c_ipauto = ($this->s103_c_ipauto == "f"?@$GLOBALS["HTTP_POST_VARS"]["s103_c_ipauto"]:$this->s103_c_ipauto);
       $this->s103_c_agendaprog = ($this->s103_c_agendaprog == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_agendaprog"]:$this->s103_c_agendaprog);
       $this->s103_i_validaagenda = ($this->s103_i_validaagenda == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_i_validaagenda"]:$this->s103_i_validaagenda);
       $this->s103_i_revisacgs = ($this->s103_i_revisacgs == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_i_revisacgs"]:$this->s103_i_revisacgs);
       $this->s103_i_tipodb = ($this->s103_i_tipodb == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_i_tipodb"]:$this->s103_i_tipodb);
       $this->s103_i_datahorafaa = ($this->s103_i_datahorafaa == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_i_datahorafaa"]:$this->s103_i_datahorafaa);
       $this->s103_i_modelofaa = ($this->s103_i_modelofaa == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_i_modelofaa"]:$this->s103_i_modelofaa);
       $this->s103_c_bpasecrdestino = ($this->s103_c_bpasecrdestino == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_bpasecrdestino"]:$this->s103_c_bpasecrdestino);
       $this->s103_c_bpasigla = ($this->s103_c_bpasigla == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_bpasigla"]:$this->s103_c_bpasigla);
       $this->s103_c_bpaibge = ($this->s103_c_bpaibge == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_c_bpaibge"]:$this->s103_c_bpaibge);
       $this->s103_i_todacomp = ($this->s103_i_todacomp == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_i_todacomp"]:$this->s103_i_todacomp);
       $this->s103_procsemcbo = ($this->s103_procsemcbo == ""?@$GLOBALS["HTTP_POST_VARS"]["s103_procsemcbo"]:$this->s103_procsemcbo);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->s103_c_emitircomprovante == null ){ 
       $this->s103_c_emitircomprovante = "S";
     }
     if($this->s103_i_departamentos == null ){ 
       $this->erro_sql = " Campo Controle UPS nao Informado.";
       $this->erro_campo = "s103_i_departamentos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s103_c_emitirfaa == null ){ 
       $this->erro_sql = " Campo Gerar FA automatica nao Informado.";
       $this->erro_campo = "s103_c_emitirfaa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s103_c_cancelafa == null ){ 
       $this->erro_sql = " Campo Cancelar FA anteriores nao Informado.";
       $this->erro_campo = "s103_c_cancelafa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s103_i_modalidade == null ){ 
       $this->s103_i_modalidade = "0";
     }
     if($this->s103_i_porta == null ){ 
       $this->s103_i_porta = "3050";
     }
     if($this->s103_c_usuario == null ){ 
       $this->s103_c_usuario = "SYSDBA";
     }
     if($this->s103_c_apareceragenda == null ){ 
       $this->erro_sql = " Campo Aparecer FAAs geradas nao Informado.";
       $this->erro_campo = "s103_c_apareceragenda";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s103_c_idadeproc == null ){ 
       $this->erro_sql = " Campo Valida Idade do Procedimento nao Informado.";
       $this->erro_campo = "s103_c_idadeproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s103_c_servicoproc == null ){ 
       $this->erro_sql = " Campo Valida Servi�o do Procedimento nao Informado.";
       $this->erro_campo = "s103_c_servicoproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s103_c_ipauto == null ){ 
       $this->s103_c_ipauto = "f";
     }
     if($this->s103_c_agendaprog == null ){ 
       $this->s103_c_agendaprog = "N";
     }
     if($this->s103_i_validaagenda == null ){ 
       $this->s103_i_validaagenda = "0";
     }
     if($this->s103_i_revisacgs == null ){ 
       $this->s103_i_revisacgs = "0";
     }
     if($this->s103_i_tipodb == null ){ 
       $this->s103_i_tipodb = "0";
     }
     if($this->s103_i_datahorafaa == null ){ 
       $this->erro_sql = " Campo Data e hora na FAA nao Informado.";
       $this->erro_campo = "s103_i_datahorafaa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s103_i_modelofaa == null ){ 
       $this->erro_sql = " Campo Modelo FA nao Informado.";
       $this->erro_campo = "s103_i_modelofaa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s103_i_todacomp == null ){ 
       $this->erro_sql = " Campo Apresentar Todas Compet�ncia nao Informado.";
       $this->erro_campo = "s103_i_todacomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     
     $sql = "insert into sau_config(
                                       s103_c_lancafaa 
                                      ,s103_v_msgagenda 
                                      ,s103_c_agendaproc 
                                      ,s103_c_emitircomprovante 
                                      ,s103_i_departamentos 
                                      ,s103_c_emitirfaa 
                                      ,s103_c_cancelafa 
                                      ,s103_i_modalidade 
                                      ,s103_c_sgdb 
                                      ,s103_c_ip 
                                      ,s103_i_porta 
                                      ,s103_c_senha 
                                      ,s103_c_usuario 
                                      ,s103_c_apareceragenda 
                                      ,s103_c_idadeproc 
                                      ,s103_c_servicoproc 
                                      ,s103_c_ipauto 
                                      ,s103_c_agendaprog 
                                      ,s103_i_validaagenda 
                                      ,s103_i_revisacgs 
                                      ,s103_i_tipodb 
                                      ,s103_i_datahorafaa 
                                      ,s103_i_modelofaa 
                                      ,s103_c_bpasecrdestino 
                                      ,s103_c_bpasigla 
                                      ,s103_c_bpaibge 
                                      ,s103_i_todacomp 
                                      ,s103_procsemcbo 
                       )
                values (
                                '$this->s103_c_lancafaa' 
                               ,'$this->s103_v_msgagenda' 
                               ,'$this->s103_c_agendaproc' 
                               ,'$this->s103_c_emitircomprovante' 
                               ,$this->s103_i_departamentos 
                               ,'$this->s103_c_emitirfaa' 
                               ,'$this->s103_c_cancelafa' 
                               ,$this->s103_i_modalidade 
                               ,'$this->s103_c_sgdb' 
                               ,'$this->s103_c_ip' 
                               ,$this->s103_i_porta 
                               ,'$this->s103_c_senha' 
                               ,'$this->s103_c_usuario' 
                               ,'$this->s103_c_apareceragenda' 
                               ,'$this->s103_c_idadeproc' 
                               ,'$this->s103_c_servicoproc' 
                               ,'$this->s103_c_ipauto' 
                               ,'$this->s103_c_agendaprog' 
                               ,$this->s103_i_validaagenda 
                               ,$this->s103_i_revisacgs 
                               ,$this->s103_i_tipodb 
                               ,$this->s103_i_datahorafaa 
                               ,$this->s103_i_modelofaa 
                               ,'$this->s103_c_bpasecrdestino' 
                               ,'$this->s103_c_bpasigla' 
                               ,'$this->s103_c_bpaibge' 
                               ,$this->s103_i_todacomp 
                               ,'$this->s103_procsemcbo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configura��o par�metros () nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configura��o par�metros j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configura��o par�metros () nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update sau_config set ";
     $virgula = "";
     if(trim($this->s103_c_lancafaa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_lancafaa"])){ 
       $sql  .= $virgula." s103_c_lancafaa = '$this->s103_c_lancafaa' ";
       $virgula = ",";
     }
     if(trim($this->s103_v_msgagenda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_v_msgagenda"])){ 
       $sql  .= $virgula." s103_v_msgagenda = '$this->s103_v_msgagenda' ";
       $virgula = ",";
     }
     if(trim($this->s103_c_agendaproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_agendaproc"])){ 
       $sql  .= $virgula." s103_c_agendaproc = '$this->s103_c_agendaproc' ";
       $virgula = ",";
     }
     if(trim($this->s103_c_emitircomprovante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_emitircomprovante"])){ 
       $sql  .= $virgula." s103_c_emitircomprovante = '$this->s103_c_emitircomprovante' ";
       $virgula = ",";
     }
     if(trim($this->s103_i_departamentos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_i_departamentos"])){ 
       $sql  .= $virgula." s103_i_departamentos = $this->s103_i_departamentos ";
       $virgula = ",";
       if(trim($this->s103_i_departamentos) == null ){ 
         $this->erro_sql = " Campo Controle UPS nao Informado.";
         $this->erro_campo = "s103_i_departamentos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s103_c_emitirfaa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_emitirfaa"])){ 
       $sql  .= $virgula." s103_c_emitirfaa = '$this->s103_c_emitirfaa' ";
       $virgula = ",";
       if(trim($this->s103_c_emitirfaa) == null ){ 
         $this->erro_sql = " Campo Gerar FA automatica nao Informado.";
         $this->erro_campo = "s103_c_emitirfaa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s103_c_cancelafa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_cancelafa"])){ 
       $sql  .= $virgula." s103_c_cancelafa = '$this->s103_c_cancelafa' ";
       $virgula = ",";
       if(trim($this->s103_c_cancelafa) == null ){ 
         $this->erro_sql = " Campo Cancelar FA anteriores nao Informado.";
         $this->erro_campo = "s103_c_cancelafa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s103_i_modalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_i_modalidade"])){ 
        if(trim($this->s103_i_modalidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s103_i_modalidade"])){ 
           $this->s103_i_modalidade = "0" ; 
        } 
       $sql  .= $virgula." s103_i_modalidade = $this->s103_i_modalidade ";
       $virgula = ",";
     }
     if(trim($this->s103_c_sgdb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_sgdb"])){ 
       $sql  .= $virgula." s103_c_sgdb = '$this->s103_c_sgdb' ";
       $virgula = ",";
     }
     if(trim($this->s103_c_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_ip"])){ 
       $sql  .= $virgula." s103_c_ip = '$this->s103_c_ip' ";
       $virgula = ",";
     }
     if(trim($this->s103_i_porta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_i_porta"])){ 
        if(trim($this->s103_i_porta)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s103_i_porta"])){ 
           $this->s103_i_porta = "0" ; 
        } 
       $sql  .= $virgula." s103_i_porta = $this->s103_i_porta ";
       $virgula = ",";
     }
     if(trim($this->s103_c_senha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_senha"])){ 
       $sql  .= $virgula." s103_c_senha = '$this->s103_c_senha' ";
       $virgula = ",";
     }
     if(trim($this->s103_c_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_usuario"])){ 
       $sql  .= $virgula." s103_c_usuario = '$this->s103_c_usuario' ";
       $virgula = ",";
     }
     if(trim($this->s103_c_apareceragenda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_apareceragenda"])){ 
       $sql  .= $virgula." s103_c_apareceragenda = '$this->s103_c_apareceragenda' ";
       $virgula = ",";
       if(trim($this->s103_c_apareceragenda) == null ){ 
         $this->erro_sql = " Campo Aparecer FAAs geradas nao Informado.";
         $this->erro_campo = "s103_c_apareceragenda";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s103_c_idadeproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_idadeproc"])){ 
       $sql  .= $virgula." s103_c_idadeproc = '$this->s103_c_idadeproc' ";
       $virgula = ",";
       if(trim($this->s103_c_idadeproc) == null ){ 
         $this->erro_sql = " Campo Valida Idade do Procedimento nao Informado.";
         $this->erro_campo = "s103_c_idadeproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s103_c_servicoproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_servicoproc"])){ 
       $sql  .= $virgula." s103_c_servicoproc = '$this->s103_c_servicoproc' ";
       $virgula = ",";
       if(trim($this->s103_c_servicoproc) == null ){ 
         $this->erro_sql = " Campo Valida Servi�o do Procedimento nao Informado.";
         $this->erro_campo = "s103_c_servicoproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s103_c_ipauto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_ipauto"])){ 
       $sql  .= $virgula." s103_c_ipauto = '$this->s103_c_ipauto' ";
       $virgula = ",";
     }
     if(trim($this->s103_c_agendaprog)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_agendaprog"])){ 
       $sql  .= $virgula." s103_c_agendaprog = '$this->s103_c_agendaprog' ";
       $virgula = ",";
     }
     if(trim($this->s103_i_validaagenda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_i_validaagenda"])){ 
        if(trim($this->s103_i_validaagenda)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s103_i_validaagenda"])){ 
           $this->s103_i_validaagenda = "0" ; 
        } 
       $sql  .= $virgula." s103_i_validaagenda = $this->s103_i_validaagenda ";
       $virgula = ",";
     }
     if(trim($this->s103_i_revisacgs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_i_revisacgs"])){ 
        if(trim($this->s103_i_revisacgs)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s103_i_revisacgs"])){ 
           $this->s103_i_revisacgs = "0" ; 
        } 
       $sql  .= $virgula." s103_i_revisacgs = $this->s103_i_revisacgs ";
       $virgula = ",";
     }
     if(trim($this->s103_i_tipodb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_i_tipodb"])){ 
        if(trim($this->s103_i_tipodb)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s103_i_tipodb"])){ 
           $this->s103_i_tipodb = "0" ; 
        } 
       $sql  .= $virgula." s103_i_tipodb = $this->s103_i_tipodb ";
       $virgula = ",";
     }
     if(trim($this->s103_i_datahorafaa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_i_datahorafaa"])){ 
       $sql  .= $virgula." s103_i_datahorafaa = $this->s103_i_datahorafaa ";
       $virgula = ",";
       if(trim($this->s103_i_datahorafaa) == null ){ 
         $this->erro_sql = " Campo Data e hora na FAA nao Informado.";
         $this->erro_campo = "s103_i_datahorafaa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s103_i_modelofaa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_i_modelofaa"])){ 
       $sql  .= $virgula." s103_i_modelofaa = $this->s103_i_modelofaa ";
       $virgula = ",";
       if(trim($this->s103_i_modelofaa) == null ){ 
         $this->erro_sql = " Campo Modelo FA nao Informado.";
         $this->erro_campo = "s103_i_modelofaa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s103_c_bpasecrdestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_bpasecrdestino"])){ 
       $sql  .= $virgula." s103_c_bpasecrdestino = '$this->s103_c_bpasecrdestino' ";
       $virgula = ",";
     }
     if(trim($this->s103_c_bpasigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_bpasigla"])){ 
       $sql  .= $virgula." s103_c_bpasigla = '$this->s103_c_bpasigla' ";
       $virgula = ",";
     }
     if(trim($this->s103_c_bpaibge)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_c_bpaibge"])){ 
       $sql  .= $virgula." s103_c_bpaibge = '$this->s103_c_bpaibge' ";
       $virgula = ",";
     }
     if(trim($this->s103_i_todacomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_i_todacomp"])){ 
       $sql  .= $virgula." s103_i_todacomp = $this->s103_i_todacomp ";
       $virgula = ",";
       if(trim($this->s103_i_todacomp) == null ){ 
         $this->erro_sql = " Campo Apresentar Todas Compet�ncia nao Informado.";
         $this->erro_campo = "s103_i_todacomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s103_procsemcbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s103_procsemcbo"])){ 
       $sql  .= $virgula." s103_procsemcbo = '$this->s103_procsemcbo' ";
       $virgula = ",";
       if(trim($this->s103_procsemcbo) == null ){ 
         $this->erro_sql = " Campo Exibir Procedimentos sem CBO nao Informado.";
         $this->erro_campo = "s103_procsemcbo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configura��o par�metros nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configura��o par�metros nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 

     $sql = " delete from sau_config
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
       $this->erro_sql   = "Configura��o par�metros nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configura��o par�metros nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:sau_config";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $oid = null,$campos="sau_config.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_config ";
     $sql .= "      left  join sau_modalidade  on  sau_modalidade.sd82_i_codigo = sau_config.s103_i_modalidade";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where sau_config.oid = '$oid'";
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
     $sql .= " from sau_config ";
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