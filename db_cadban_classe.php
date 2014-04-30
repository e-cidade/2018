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

//MODULO: caixa
//CLASSE DA ENTIDADE cadban
class cl_cadban { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $k15_codigo = 0; 
   var $k15_numcgm = 0; 
   var $k15_codbco = 0; 
   var $k15_codage = null; 
   var $k15_contat = null; 
   var $k15_gerent = null; 
   var $k15_agenci = null; 
   var $k15_conv1 = null; 
   var $k15_conv2 = null; 
   var $k15_conv3 = null; 
   var $k15_conv4 = null; 
   var $k15_conv5 = null; 
   var $k15_seq1 = 0; 
   var $k15_seq2 = 0; 
   var $k15_seq3 = 0; 
   var $k15_seq4 = 0; 
   var $k15_seq5 = 0; 
   var $k15_ceden1 = null; 
   var $k15_ceden2 = null; 
   var $k15_ceden3 = null; 
   var $k15_ceden4 = null; 
   var $k15_ceden5 = null; 
   var $k15_posbco = null; 
   var $k15_poslan = null; 
   var $k15_pospag = null; 
   var $k15_posvlr = null; 
   var $k15_posacr = null; 
   var $k15_posdes = null; 
   var $k15_posced = null; 
   var $k15_poscon = null; 
   var $k15_seq = null; 
   var $k15_conta = 0; 
   var $k15_rectxb = 0; 
   var $k15_txban = 0; 
   var $k15_local = null; 
   var $k15_carte = null; 
   var $k15_espec = null; 
   var $k15_aceite = null; 
   var $k15_ageced = null; 
   var $k15_posjur = null; 
   var $k15_posmul = null; 
   var $k15_taman = 0; 
   var $k15_posdta = null; 
   var $k15_numbco = null; 
   var $k15_numpre = null; 
   var $k15_numpar = null; 
   var $k15_plmes = null; 
   var $k15_plano = null; 
   var $k15_pdmes = null; 
   var $k15_pdano = null; 
   var $k15_ppmes = null; 
   var $k15_ppano = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k15_codigo = int4 = Código 
                 k15_numcgm = int4 = cgm 
                 k15_codbco = int4 = Banco 
                 k15_codage = char(5) = Agência 
                 k15_contat = char(30) = contato 
                 k15_gerent = char(30) = gerente 
                 k15_agenci = char(40) = descricao da agencia 
                 k15_conv1 = char(6) = convenio 1 
                 k15_conv2 = char(6) = convenio 2 
                 k15_conv3 = char(6) = convenio 3 
                 k15_conv4 = char(6) = convenio 4 
                 k15_conv5 = char(6) = convenio 6 
                 k15_seq1 = int4 = sequencia 1 
                 k15_seq2 = int4 = sequencia 2 
                 k15_seq3 = int4 = sequencia 3 
                 k15_seq4 = int4 = sequencia 4 
                 k15_seq5 = int4 = sequencia 5 
                 k15_ceden1 = char(6) = cedente 1 
                 k15_ceden2 = char(6) = cedente 2 
                 k15_ceden3 = char(6) = cedente 3 
                 k15_ceden4 = char(6) = cedente 4 
                 k15_ceden5 = char(6) = cedente 5 
                 k15_posbco = char(6) = posicao banco 
                 k15_poslan = char(6) = Posicao Dia 
                 k15_pospag = char(6) = Posicao Dia 
                 k15_posvlr = char(6) = posicao valor 
                 k15_posacr = char(6) = posicao acrescimo 
                 k15_posdes = char(6) = posicao desconto 
                 k15_posced = char(6) = posicao cedente 
                 k15_poscon = char(6) = Posição Abatimento 
                 k15_seq = char(2) = sequencia 
                 k15_conta = int4 = conta 
                 k15_rectxb = int4 = receita da taxa bancaria 
                 k15_txban = float8 = valor da taxa bancaria 
                 k15_local = char(40) = local 
                 k15_carte = char(2) = carteira 
                 k15_espec = char(20) = especie do documento 
                 k15_aceite = char(10) = aceite 
                 k15_ageced = char(30) = agencia do cedente 
                 k15_posjur = char(6) = Posição Juros 
                 k15_posmul = char(6) = Posição Multa 
                 k15_taman = int4 = Tamanho registro 
                 k15_posdta = char(6) = Posição Dia 
                 k15_numbco = varchar(15) = Codigo do Banco 
                 k15_numpre = char(6) = Posição do numpre no arquivo txt 
                 k15_numpar = char(6) = Posição do numpar no arquivo txt 
                 k15_plmes = char(6) = Posicao Mes 
                 k15_plano = char(6) = Posicao Ano 
                 k15_pdmes = char(6) = Posicao Mes 
                 k15_pdano = char(6) = Posicao Ano 
                 k15_ppmes = char(6) = Posicao Mes 
                 k15_ppano = char(6) = Posicao Ano 
                 ";
   //funcao construtor da classe 
   function cl_cadban() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadban"); 
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
       $this->k15_codigo = ($this->k15_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_codigo"]:$this->k15_codigo);
       $this->k15_numcgm = ($this->k15_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_numcgm"]:$this->k15_numcgm);
       $this->k15_codbco = ($this->k15_codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_codbco"]:$this->k15_codbco);
       $this->k15_codage = ($this->k15_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_codage"]:$this->k15_codage);
       $this->k15_contat = ($this->k15_contat == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_contat"]:$this->k15_contat);
       $this->k15_gerent = ($this->k15_gerent == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_gerent"]:$this->k15_gerent);
       $this->k15_agenci = ($this->k15_agenci == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_agenci"]:$this->k15_agenci);
       $this->k15_conv1 = ($this->k15_conv1 == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_conv1"]:$this->k15_conv1);
       $this->k15_conv2 = ($this->k15_conv2 == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_conv2"]:$this->k15_conv2);
       $this->k15_conv3 = ($this->k15_conv3 == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_conv3"]:$this->k15_conv3);
       $this->k15_conv4 = ($this->k15_conv4 == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_conv4"]:$this->k15_conv4);
       $this->k15_conv5 = ($this->k15_conv5 == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_conv5"]:$this->k15_conv5);
       $this->k15_seq1 = ($this->k15_seq1 == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_seq1"]:$this->k15_seq1);
       $this->k15_seq2 = ($this->k15_seq2 == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_seq2"]:$this->k15_seq2);
       $this->k15_seq3 = ($this->k15_seq3 == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_seq3"]:$this->k15_seq3);
       $this->k15_seq4 = ($this->k15_seq4 == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_seq4"]:$this->k15_seq4);
       $this->k15_seq5 = ($this->k15_seq5 == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_seq5"]:$this->k15_seq5);
       $this->k15_ceden1 = ($this->k15_ceden1 == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_ceden1"]:$this->k15_ceden1);
       $this->k15_ceden2 = ($this->k15_ceden2 == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_ceden2"]:$this->k15_ceden2);
       $this->k15_ceden3 = ($this->k15_ceden3 == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_ceden3"]:$this->k15_ceden3);
       $this->k15_ceden4 = ($this->k15_ceden4 == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_ceden4"]:$this->k15_ceden4);
       $this->k15_ceden5 = ($this->k15_ceden5 == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_ceden5"]:$this->k15_ceden5);
       $this->k15_posbco = ($this->k15_posbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_posbco"]:$this->k15_posbco);
       $this->k15_poslan = ($this->k15_poslan == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_poslan"]:$this->k15_poslan);
       $this->k15_pospag = ($this->k15_pospag == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_pospag"]:$this->k15_pospag);
       $this->k15_posvlr = ($this->k15_posvlr == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_posvlr"]:$this->k15_posvlr);
       $this->k15_posacr = ($this->k15_posacr == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_posacr"]:$this->k15_posacr);
       $this->k15_posdes = ($this->k15_posdes == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_posdes"]:$this->k15_posdes);
       $this->k15_posced = ($this->k15_posced == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_posced"]:$this->k15_posced);
       $this->k15_poscon = ($this->k15_poscon == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_poscon"]:$this->k15_poscon);
       $this->k15_seq = ($this->k15_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_seq"]:$this->k15_seq);
       $this->k15_conta = ($this->k15_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_conta"]:$this->k15_conta);
       $this->k15_rectxb = ($this->k15_rectxb == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_rectxb"]:$this->k15_rectxb);
       $this->k15_txban = ($this->k15_txban == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_txban"]:$this->k15_txban);
       $this->k15_local = ($this->k15_local == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_local"]:$this->k15_local);
       $this->k15_carte = ($this->k15_carte == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_carte"]:$this->k15_carte);
       $this->k15_espec = ($this->k15_espec == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_espec"]:$this->k15_espec);
       $this->k15_aceite = ($this->k15_aceite == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_aceite"]:$this->k15_aceite);
       $this->k15_ageced = ($this->k15_ageced == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_ageced"]:$this->k15_ageced);
       $this->k15_posjur = ($this->k15_posjur == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_posjur"]:$this->k15_posjur);
       $this->k15_posmul = ($this->k15_posmul == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_posmul"]:$this->k15_posmul);
       $this->k15_taman = ($this->k15_taman == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_taman"]:$this->k15_taman);
       $this->k15_posdta = ($this->k15_posdta == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_posdta"]:$this->k15_posdta);
       $this->k15_numbco = ($this->k15_numbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_numbco"]:$this->k15_numbco);
       $this->k15_numpre = ($this->k15_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_numpre"]:$this->k15_numpre);
       $this->k15_numpar = ($this->k15_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_numpar"]:$this->k15_numpar);
       $this->k15_plmes = ($this->k15_plmes == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_plmes"]:$this->k15_plmes);
       $this->k15_plano = ($this->k15_plano == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_plano"]:$this->k15_plano);
       $this->k15_pdmes = ($this->k15_pdmes == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_pdmes"]:$this->k15_pdmes);
       $this->k15_pdano = ($this->k15_pdano == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_pdano"]:$this->k15_pdano);
       $this->k15_ppmes = ($this->k15_ppmes == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_ppmes"]:$this->k15_ppmes);
       $this->k15_ppano = ($this->k15_ppano == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_ppano"]:$this->k15_ppano);
     }else{
       $this->k15_codigo = ($this->k15_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_codigo"]:$this->k15_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($k15_codigo){ 
      $this->atualizacampos();
     if($this->k15_numcgm == null ){ 
       $this->erro_sql = " Campo cgm nao Informado.";
       $this->erro_campo = "k15_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_codbco == null ){ 
       $this->erro_sql = " Campo Banco nao Informado.";
       $this->erro_campo = "k15_codbco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_codage == null ){ 
       $this->erro_sql = " Campo Agência nao Informado.";
       $this->erro_campo = "k15_codage";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_contat == null ){ 
       $this->erro_sql = " Campo contato nao Informado.";
       $this->erro_campo = "k15_contat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_gerent == null ){ 
       $this->erro_sql = " Campo gerente nao Informado.";
       $this->erro_campo = "k15_gerent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_agenci == null ){ 
       $this->erro_sql = " Campo descricao da agencia nao Informado.";
       $this->erro_campo = "k15_agenci";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_conv1 == null ){ 
       $this->erro_sql = " Campo convenio 1 nao Informado.";
       $this->erro_campo = "k15_conv1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_conv2 == null ){ 
       $this->erro_sql = " Campo convenio 2 nao Informado.";
       $this->erro_campo = "k15_conv2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_conv3 == null ){ 
       $this->erro_sql = " Campo convenio 3 nao Informado.";
       $this->erro_campo = "k15_conv3";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_conv4 == null ){ 
       $this->erro_sql = " Campo convenio 4 nao Informado.";
       $this->erro_campo = "k15_conv4";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_conv5 == null ){ 
       $this->erro_sql = " Campo convenio 6 nao Informado.";
       $this->erro_campo = "k15_conv5";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_seq1 == null ){ 
       $this->erro_sql = " Campo sequencia 1 nao Informado.";
       $this->erro_campo = "k15_seq1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_seq2 == null ){ 
       $this->erro_sql = " Campo sequencia 2 nao Informado.";
       $this->erro_campo = "k15_seq2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_seq3 == null ){ 
       $this->erro_sql = " Campo sequencia 3 nao Informado.";
       $this->erro_campo = "k15_seq3";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_seq4 == null ){ 
       $this->erro_sql = " Campo sequencia 4 nao Informado.";
       $this->erro_campo = "k15_seq4";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_seq5 == null ){ 
       $this->erro_sql = " Campo sequencia 5 nao Informado.";
       $this->erro_campo = "k15_seq5";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_ceden1 == null ){ 
       $this->erro_sql = " Campo cedente 1 nao Informado.";
       $this->erro_campo = "k15_ceden1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_ceden2 == null ){ 
       $this->erro_sql = " Campo cedente 2 nao Informado.";
       $this->erro_campo = "k15_ceden2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_ceden3 == null ){ 
       $this->erro_sql = " Campo cedente 3 nao Informado.";
       $this->erro_campo = "k15_ceden3";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_ceden4 == null ){ 
       $this->erro_sql = " Campo cedente 4 nao Informado.";
       $this->erro_campo = "k15_ceden4";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_ceden5 == null ){ 
       $this->erro_sql = " Campo cedente 5 nao Informado.";
       $this->erro_campo = "k15_ceden5";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_posbco == null ){ 
       $this->erro_sql = " Campo posicao banco nao Informado.";
       $this->erro_campo = "k15_posbco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_poslan == null ){ 
       $this->erro_sql = " Campo Posicao Dia nao Informado.";
       $this->erro_campo = "k15_poslan";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_pospag == null ){ 
       $this->erro_sql = " Campo Posicao Dia nao Informado.";
       $this->erro_campo = "k15_pospag";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_posvlr == null ){ 
       $this->erro_sql = " Campo posicao valor nao Informado.";
       $this->erro_campo = "k15_posvlr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_posacr == null ){ 
       $this->erro_sql = " Campo posicao acrescimo nao Informado.";
       $this->erro_campo = "k15_posacr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_posdes == null ){ 
       $this->erro_sql = " Campo posicao desconto nao Informado.";
       $this->erro_campo = "k15_posdes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_posced == null ){ 
       $this->erro_sql = " Campo posicao cedente nao Informado.";
       $this->erro_campo = "k15_posced";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_poscon == null ){ 
       $this->erro_sql = " Campo Posição Abatimento nao Informado.";
       $this->erro_campo = "k15_poscon";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_seq == null ){ 
       $this->erro_sql = " Campo sequencia nao Informado.";
       $this->erro_campo = "k15_seq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_conta == null ){ 
       $this->erro_sql = " Campo conta nao Informado.";
       $this->erro_campo = "k15_conta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_rectxb == null ){ 
       $this->erro_sql = " Campo receita da taxa bancaria nao Informado.";
       $this->erro_campo = "k15_rectxb";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_txban == null ){ 
       $this->erro_sql = " Campo valor da taxa bancaria nao Informado.";
       $this->erro_campo = "k15_txban";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_local == null ){ 
       $this->erro_sql = " Campo local nao Informado.";
       $this->erro_campo = "k15_local";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_carte == null ){ 
       $this->erro_sql = " Campo carteira nao Informado.";
       $this->erro_campo = "k15_carte";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_espec == null ){ 
       $this->erro_sql = " Campo especie do documento nao Informado.";
       $this->erro_campo = "k15_espec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_aceite == null ){ 
       $this->erro_sql = " Campo aceite nao Informado.";
       $this->erro_campo = "k15_aceite";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_ageced == null ){ 
       $this->erro_sql = " Campo agencia do cedente nao Informado.";
       $this->erro_campo = "k15_ageced";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_posjur == null ){ 
       $this->erro_sql = " Campo Posição Juros nao Informado.";
       $this->erro_campo = "k15_posjur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_taman == null ){ 
       $this->erro_sql = " Campo Tamanho registro nao Informado.";
       $this->erro_campo = "k15_taman";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_posdta == null ){ 
       $this->erro_sql = " Campo Posição Dia nao Informado.";
       $this->erro_campo = "k15_posdta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_numbco == null ){ 
       $this->erro_sql = " Campo Codigo do Banco nao Informado.";
       $this->erro_campo = "k15_numbco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_numpre == null ){ 
       $this->erro_sql = " Campo Posição do numpre no arquivo txt nao Informado.";
       $this->erro_campo = "k15_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_numpar == null ){ 
       $this->erro_sql = " Campo Posição do numpar no arquivo txt nao Informado.";
       $this->erro_campo = "k15_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_plmes == null ){ 
       $this->erro_sql = " Campo Posicao Mes nao Informado.";
       $this->erro_campo = "k15_plmes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_plano == null ){ 
       $this->erro_sql = " Campo Posicao Ano nao Informado.";
       $this->erro_campo = "k15_plano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_pdmes == null ){ 
       $this->erro_sql = " Campo Posicao Mes nao Informado.";
       $this->erro_campo = "k15_pdmes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_pdano == null ){ 
       $this->erro_sql = " Campo Posicao Ano nao Informado.";
       $this->erro_campo = "k15_pdano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_ppmes == null ){ 
       $this->erro_sql = " Campo Posicao Mes nao Informado.";
       $this->erro_campo = "k15_ppmes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_ppano == null ){ 
       $this->erro_sql = " Campo Posicao Ano nao Informado.";
       $this->erro_campo = "k15_ppano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k15_codigo == "" || $k15_codigo == null ){
       $result = @pg_query("select nextval('cadban_k15_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadban_k15_codigo_seq do campo: k15_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k15_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from cadban_k15_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $k15_codigo)){
         $this->erro_sql = " Campo k15_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k15_codigo = $k15_codigo; 
       }
     }
     if(($this->k15_codigo == null) || ($this->k15_codigo == "") ){ 
       $this->erro_sql = " Campo k15_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadban(
                                       k15_codigo 
                                      ,k15_numcgm 
                                      ,k15_codbco 
                                      ,k15_codage 
                                      ,k15_contat 
                                      ,k15_gerent 
                                      ,k15_agenci 
                                      ,k15_conv1 
                                      ,k15_conv2 
                                      ,k15_conv3 
                                      ,k15_conv4 
                                      ,k15_conv5 
                                      ,k15_seq1 
                                      ,k15_seq2 
                                      ,k15_seq3 
                                      ,k15_seq4 
                                      ,k15_seq5 
                                      ,k15_ceden1 
                                      ,k15_ceden2 
                                      ,k15_ceden3 
                                      ,k15_ceden4 
                                      ,k15_ceden5 
                                      ,k15_posbco 
                                      ,k15_poslan 
                                      ,k15_pospag 
                                      ,k15_posvlr 
                                      ,k15_posacr 
                                      ,k15_posdes 
                                      ,k15_posced 
                                      ,k15_poscon 
                                      ,k15_seq 
                                      ,k15_conta 
                                      ,k15_rectxb 
                                      ,k15_txban 
                                      ,k15_local 
                                      ,k15_carte 
                                      ,k15_espec 
                                      ,k15_aceite 
                                      ,k15_ageced 
                                      ,k15_posjur 
                                      ,k15_posmul 
                                      ,k15_taman 
                                      ,k15_posdta 
                                      ,k15_numbco 
                                      ,k15_numpre 
                                      ,k15_numpar 
                                      ,k15_plmes 
                                      ,k15_plano 
                                      ,k15_pdmes 
                                      ,k15_pdano 
                                      ,k15_ppmes 
                                      ,k15_ppano 
                       )
                values (
                                $this->k15_codigo 
                               ,$this->k15_numcgm 
                               ,$this->k15_codbco 
                               ,'$this->k15_codage' 
                               ,'$this->k15_contat' 
                               ,'$this->k15_gerent' 
                               ,'$this->k15_agenci' 
                               ,'$this->k15_conv1' 
                               ,'$this->k15_conv2' 
                               ,'$this->k15_conv3' 
                               ,'$this->k15_conv4' 
                               ,'$this->k15_conv5' 
                               ,$this->k15_seq1 
                               ,$this->k15_seq2 
                               ,$this->k15_seq3 
                               ,$this->k15_seq4 
                               ,$this->k15_seq5 
                               ,'$this->k15_ceden1' 
                               ,'$this->k15_ceden2' 
                               ,'$this->k15_ceden3' 
                               ,'$this->k15_ceden4' 
                               ,'$this->k15_ceden5' 
                               ,'$this->k15_posbco' 
                               ,'$this->k15_poslan' 
                               ,'$this->k15_pospag' 
                               ,'$this->k15_posvlr' 
                               ,'$this->k15_posacr' 
                               ,'$this->k15_posdes' 
                               ,'$this->k15_posced' 
                               ,'$this->k15_poscon' 
                               ,'$this->k15_seq' 
                               ,$this->k15_conta 
                               ,$this->k15_rectxb 
                               ,$this->k15_txban 
                               ,'$this->k15_local' 
                               ,'$this->k15_carte' 
                               ,'$this->k15_espec' 
                               ,'$this->k15_aceite' 
                               ,'$this->k15_ageced' 
                               ,'$this->k15_posjur' 
                               ,'$this->k15_posmul' 
                               ,$this->k15_taman 
                               ,'$this->k15_posdta' 
                               ,'$this->k15_numbco' 
                               ,'$this->k15_numpre' 
                               ,'$this->k15_numpar' 
                               ,'$this->k15_plmes' 
                               ,'$this->k15_plano' 
                               ,'$this->k15_pdmes' 
                               ,'$this->k15_pdano' 
                               ,'$this->k15_ppmes' 
                               ,'$this->k15_ppano' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->k15_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->k15_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k15_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->k15_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,4841,'$this->k15_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,116,4841,'','".pg_result($resaco,0,'k15_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,585,'','".pg_result($resaco,0,'k15_numcgm')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,586,'','".pg_result($resaco,0,'k15_codbco')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,587,'','".pg_result($resaco,0,'k15_codage')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,588,'','".pg_result($resaco,0,'k15_contat')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,589,'','".pg_result($resaco,0,'k15_gerent')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,590,'','".pg_result($resaco,0,'k15_agenci')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,595,'','".pg_result($resaco,0,'k15_conv1')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,596,'','".pg_result($resaco,0,'k15_conv2')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,597,'','".pg_result($resaco,0,'k15_conv3')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,599,'','".pg_result($resaco,0,'k15_conv4')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,600,'','".pg_result($resaco,0,'k15_conv5')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,603,'','".pg_result($resaco,0,'k15_seq1')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,604,'','".pg_result($resaco,0,'k15_seq2')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,606,'','".pg_result($resaco,0,'k15_seq3')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,607,'','".pg_result($resaco,0,'k15_seq4')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,608,'','".pg_result($resaco,0,'k15_seq5')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,610,'','".pg_result($resaco,0,'k15_ceden1')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,611,'','".pg_result($resaco,0,'k15_ceden2')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,613,'','".pg_result($resaco,0,'k15_ceden3')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,614,'','".pg_result($resaco,0,'k15_ceden4')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,616,'','".pg_result($resaco,0,'k15_ceden5')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,617,'','".pg_result($resaco,0,'k15_posbco')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,619,'','".pg_result($resaco,0,'k15_poslan')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,620,'','".pg_result($resaco,0,'k15_pospag')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,621,'','".pg_result($resaco,0,'k15_posvlr')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,622,'','".pg_result($resaco,0,'k15_posacr')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,623,'','".pg_result($resaco,0,'k15_posdes')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,624,'','".pg_result($resaco,0,'k15_posced')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,625,'','".pg_result($resaco,0,'k15_poscon')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,626,'','".pg_result($resaco,0,'k15_seq')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,627,'','".pg_result($resaco,0,'k15_conta')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,629,'','".pg_result($resaco,0,'k15_rectxb')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,630,'','".pg_result($resaco,0,'k15_txban')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,631,'','".pg_result($resaco,0,'k15_local')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,632,'','".pg_result($resaco,0,'k15_carte')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,633,'','".pg_result($resaco,0,'k15_espec')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,634,'','".pg_result($resaco,0,'k15_aceite')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,635,'','".pg_result($resaco,0,'k15_ageced')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,1093,'','".pg_result($resaco,0,'k15_posjur')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,1094,'','".pg_result($resaco,0,'k15_posmul')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,1890,'','".pg_result($resaco,0,'k15_taman')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,1894,'','".pg_result($resaco,0,'k15_posdta')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,2385,'','".pg_result($resaco,0,'k15_numbco')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,4842,'','".pg_result($resaco,0,'k15_numpre')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,4843,'','".pg_result($resaco,0,'k15_numpar')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,2402,'','".pg_result($resaco,0,'k15_plmes')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,2403,'','".pg_result($resaco,0,'k15_plano')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,2404,'','".pg_result($resaco,0,'k15_pdmes')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,2405,'','".pg_result($resaco,0,'k15_pdano')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,2406,'','".pg_result($resaco,0,'k15_ppmes')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,116,2407,'','".pg_result($resaco,0,'k15_ppano')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k15_codigo=null) { 
      $this->atualizacampos();
     $sql = " update cadban set ";
     $virgula = "";
     if(trim($this->k15_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_codigo"])){ 
        if(trim($this->k15_codigo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k15_codigo"])){ 
           $this->k15_codigo = "0" ; 
        } 
       $sql  .= $virgula." k15_codigo = $this->k15_codigo ";
       $virgula = ",";
       if(trim($this->k15_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "k15_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_numcgm"])){ 
        if(trim($this->k15_numcgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k15_numcgm"])){ 
           $this->k15_numcgm = "0" ; 
        } 
       $sql  .= $virgula." k15_numcgm = $this->k15_numcgm ";
       $virgula = ",";
       if(trim($this->k15_numcgm) == null ){ 
         $this->erro_sql = " Campo cgm nao Informado.";
         $this->erro_campo = "k15_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_codbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_codbco"])){ 
        if(trim($this->k15_codbco)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k15_codbco"])){ 
           $this->k15_codbco = "0" ; 
        } 
       $sql  .= $virgula." k15_codbco = $this->k15_codbco ";
       $virgula = ",";
       if(trim($this->k15_codbco) == null ){ 
         $this->erro_sql = " Campo Banco nao Informado.";
         $this->erro_campo = "k15_codbco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_codage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_codage"])){ 
       $sql  .= $virgula." k15_codage = '$this->k15_codage' ";
       $virgula = ",";
       if(trim($this->k15_codage) == null ){ 
         $this->erro_sql = " Campo Agência nao Informado.";
         $this->erro_campo = "k15_codage";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_contat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_contat"])){ 
       $sql  .= $virgula." k15_contat = '$this->k15_contat' ";
       $virgula = ",";
       if(trim($this->k15_contat) == null ){ 
         $this->erro_sql = " Campo contato nao Informado.";
         $this->erro_campo = "k15_contat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_gerent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_gerent"])){ 
       $sql  .= $virgula." k15_gerent = '$this->k15_gerent' ";
       $virgula = ",";
       if(trim($this->k15_gerent) == null ){ 
         $this->erro_sql = " Campo gerente nao Informado.";
         $this->erro_campo = "k15_gerent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_agenci)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_agenci"])){ 
       $sql  .= $virgula." k15_agenci = '$this->k15_agenci' ";
       $virgula = ",";
       if(trim($this->k15_agenci) == null ){ 
         $this->erro_sql = " Campo descricao da agencia nao Informado.";
         $this->erro_campo = "k15_agenci";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_conv1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_conv1"])){ 
       $sql  .= $virgula." k15_conv1 = '$this->k15_conv1' ";
       $virgula = ",";
       if(trim($this->k15_conv1) == null ){ 
         $this->erro_sql = " Campo convenio 1 nao Informado.";
         $this->erro_campo = "k15_conv1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_conv2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_conv2"])){ 
       $sql  .= $virgula." k15_conv2 = '$this->k15_conv2' ";
       $virgula = ",";
       if(trim($this->k15_conv2) == null ){ 
         $this->erro_sql = " Campo convenio 2 nao Informado.";
         $this->erro_campo = "k15_conv2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_conv3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_conv3"])){ 
       $sql  .= $virgula." k15_conv3 = '$this->k15_conv3' ";
       $virgula = ",";
       if(trim($this->k15_conv3) == null ){ 
         $this->erro_sql = " Campo convenio 3 nao Informado.";
         $this->erro_campo = "k15_conv3";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_conv4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_conv4"])){ 
       $sql  .= $virgula." k15_conv4 = '$this->k15_conv4' ";
       $virgula = ",";
       if(trim($this->k15_conv4) == null ){ 
         $this->erro_sql = " Campo convenio 4 nao Informado.";
         $this->erro_campo = "k15_conv4";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_conv5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_conv5"])){ 
       $sql  .= $virgula." k15_conv5 = '$this->k15_conv5' ";
       $virgula = ",";
       if(trim($this->k15_conv5) == null ){ 
         $this->erro_sql = " Campo convenio 6 nao Informado.";
         $this->erro_campo = "k15_conv5";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_seq1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_seq1"])){ 
        if(trim($this->k15_seq1)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k15_seq1"])){ 
           $this->k15_seq1 = "0" ; 
        } 
       $sql  .= $virgula." k15_seq1 = $this->k15_seq1 ";
       $virgula = ",";
       if(trim($this->k15_seq1) == null ){ 
         $this->erro_sql = " Campo sequencia 1 nao Informado.";
         $this->erro_campo = "k15_seq1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_seq2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_seq2"])){ 
        if(trim($this->k15_seq2)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k15_seq2"])){ 
           $this->k15_seq2 = "0" ; 
        } 
       $sql  .= $virgula." k15_seq2 = $this->k15_seq2 ";
       $virgula = ",";
       if(trim($this->k15_seq2) == null ){ 
         $this->erro_sql = " Campo sequencia 2 nao Informado.";
         $this->erro_campo = "k15_seq2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_seq3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_seq3"])){ 
        if(trim($this->k15_seq3)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k15_seq3"])){ 
           $this->k15_seq3 = "0" ; 
        } 
       $sql  .= $virgula." k15_seq3 = $this->k15_seq3 ";
       $virgula = ",";
       if(trim($this->k15_seq3) == null ){ 
         $this->erro_sql = " Campo sequencia 3 nao Informado.";
         $this->erro_campo = "k15_seq3";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_seq4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_seq4"])){ 
        if(trim($this->k15_seq4)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k15_seq4"])){ 
           $this->k15_seq4 = "0" ; 
        } 
       $sql  .= $virgula." k15_seq4 = $this->k15_seq4 ";
       $virgula = ",";
       if(trim($this->k15_seq4) == null ){ 
         $this->erro_sql = " Campo sequencia 4 nao Informado.";
         $this->erro_campo = "k15_seq4";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_seq5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_seq5"])){ 
        if(trim($this->k15_seq5)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k15_seq5"])){ 
           $this->k15_seq5 = "0" ; 
        } 
       $sql  .= $virgula." k15_seq5 = $this->k15_seq5 ";
       $virgula = ",";
       if(trim($this->k15_seq5) == null ){ 
         $this->erro_sql = " Campo sequencia 5 nao Informado.";
         $this->erro_campo = "k15_seq5";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_ceden1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_ceden1"])){ 
       $sql  .= $virgula." k15_ceden1 = '$this->k15_ceden1' ";
       $virgula = ",";
       if(trim($this->k15_ceden1) == null ){ 
         $this->erro_sql = " Campo cedente 1 nao Informado.";
         $this->erro_campo = "k15_ceden1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_ceden2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_ceden2"])){ 
       $sql  .= $virgula." k15_ceden2 = '$this->k15_ceden2' ";
       $virgula = ",";
       if(trim($this->k15_ceden2) == null ){ 
         $this->erro_sql = " Campo cedente 2 nao Informado.";
         $this->erro_campo = "k15_ceden2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_ceden3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_ceden3"])){ 
       $sql  .= $virgula." k15_ceden3 = '$this->k15_ceden3' ";
       $virgula = ",";
       if(trim($this->k15_ceden3) == null ){ 
         $this->erro_sql = " Campo cedente 3 nao Informado.";
         $this->erro_campo = "k15_ceden3";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_ceden4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_ceden4"])){ 
       $sql  .= $virgula." k15_ceden4 = '$this->k15_ceden4' ";
       $virgula = ",";
       if(trim($this->k15_ceden4) == null ){ 
         $this->erro_sql = " Campo cedente 4 nao Informado.";
         $this->erro_campo = "k15_ceden4";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_ceden5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_ceden5"])){ 
       $sql  .= $virgula." k15_ceden5 = '$this->k15_ceden5' ";
       $virgula = ",";
       if(trim($this->k15_ceden5) == null ){ 
         $this->erro_sql = " Campo cedente 5 nao Informado.";
         $this->erro_campo = "k15_ceden5";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_posbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_posbco"])){ 
       $sql  .= $virgula." k15_posbco = '$this->k15_posbco' ";
       $virgula = ",";
       if(trim($this->k15_posbco) == null ){ 
         $this->erro_sql = " Campo posicao banco nao Informado.";
         $this->erro_campo = "k15_posbco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_poslan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_poslan"])){ 
       $sql  .= $virgula." k15_poslan = '$this->k15_poslan' ";
       $virgula = ",";
       if(trim($this->k15_poslan) == null ){ 
         $this->erro_sql = " Campo Posicao Dia nao Informado.";
         $this->erro_campo = "k15_poslan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_pospag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_pospag"])){ 
       $sql  .= $virgula." k15_pospag = '$this->k15_pospag' ";
       $virgula = ",";
       if(trim($this->k15_pospag) == null ){ 
         $this->erro_sql = " Campo Posicao Dia nao Informado.";
         $this->erro_campo = "k15_pospag";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_posvlr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_posvlr"])){ 
       $sql  .= $virgula." k15_posvlr = '$this->k15_posvlr' ";
       $virgula = ",";
       if(trim($this->k15_posvlr) == null ){ 
         $this->erro_sql = " Campo posicao valor nao Informado.";
         $this->erro_campo = "k15_posvlr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_posacr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_posacr"])){ 
       $sql  .= $virgula." k15_posacr = '$this->k15_posacr' ";
       $virgula = ",";
       if(trim($this->k15_posacr) == null ){ 
         $this->erro_sql = " Campo posicao acrescimo nao Informado.";
         $this->erro_campo = "k15_posacr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_posdes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_posdes"])){ 
       $sql  .= $virgula." k15_posdes = '$this->k15_posdes' ";
       $virgula = ",";
       if(trim($this->k15_posdes) == null ){ 
         $this->erro_sql = " Campo posicao desconto nao Informado.";
         $this->erro_campo = "k15_posdes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_posced)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_posced"])){ 
       $sql  .= $virgula." k15_posced = '$this->k15_posced' ";
       $virgula = ",";
       if(trim($this->k15_posced) == null ){ 
         $this->erro_sql = " Campo posicao cedente nao Informado.";
         $this->erro_campo = "k15_posced";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_poscon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_poscon"])){ 
       $sql  .= $virgula." k15_poscon = '$this->k15_poscon' ";
       $virgula = ",";
       if(trim($this->k15_poscon) == null ){ 
         $this->erro_sql = " Campo Posição Abatimento nao Informado.";
         $this->erro_campo = "k15_poscon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_seq"])){ 
       $sql  .= $virgula." k15_seq = '$this->k15_seq' ";
       $virgula = ",";
       if(trim($this->k15_seq) == null ){ 
         $this->erro_sql = " Campo sequencia nao Informado.";
         $this->erro_campo = "k15_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_conta"])){ 
        if(trim($this->k15_conta)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k15_conta"])){ 
           $this->k15_conta = "0" ; 
        } 
       $sql  .= $virgula." k15_conta = $this->k15_conta ";
       $virgula = ",";
       if(trim($this->k15_conta) == null ){ 
         $this->erro_sql = " Campo conta nao Informado.";
         $this->erro_campo = "k15_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_rectxb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_rectxb"])){ 
        if(trim($this->k15_rectxb)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k15_rectxb"])){ 
           $this->k15_rectxb = "0" ; 
        } 
       $sql  .= $virgula." k15_rectxb = $this->k15_rectxb ";
       $virgula = ",";
       if(trim($this->k15_rectxb) == null ){ 
         $this->erro_sql = " Campo receita da taxa bancaria nao Informado.";
         $this->erro_campo = "k15_rectxb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_txban)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_txban"])){ 
        if(trim($this->k15_txban)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k15_txban"])){ 
           $this->k15_txban = "0" ; 
        } 
       $sql  .= $virgula." k15_txban = $this->k15_txban ";
       $virgula = ",";
       if(trim($this->k15_txban) == null ){ 
         $this->erro_sql = " Campo valor da taxa bancaria nao Informado.";
         $this->erro_campo = "k15_txban";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_local)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_local"])){ 
       $sql  .= $virgula." k15_local = '$this->k15_local' ";
       $virgula = ",";
       if(trim($this->k15_local) == null ){ 
         $this->erro_sql = " Campo local nao Informado.";
         $this->erro_campo = "k15_local";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_carte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_carte"])){ 
       $sql  .= $virgula." k15_carte = '$this->k15_carte' ";
       $virgula = ",";
       if(trim($this->k15_carte) == null ){ 
         $this->erro_sql = " Campo carteira nao Informado.";
         $this->erro_campo = "k15_carte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_espec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_espec"])){ 
       $sql  .= $virgula." k15_espec = '$this->k15_espec' ";
       $virgula = ",";
       if(trim($this->k15_espec) == null ){ 
         $this->erro_sql = " Campo especie do documento nao Informado.";
         $this->erro_campo = "k15_espec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_aceite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_aceite"])){ 
       $sql  .= $virgula." k15_aceite = '$this->k15_aceite' ";
       $virgula = ",";
       if(trim($this->k15_aceite) == null ){ 
         $this->erro_sql = " Campo aceite nao Informado.";
         $this->erro_campo = "k15_aceite";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_ageced)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_ageced"])){ 
       $sql  .= $virgula." k15_ageced = '$this->k15_ageced' ";
       $virgula = ",";
       if(trim($this->k15_ageced) == null ){ 
         $this->erro_sql = " Campo agencia do cedente nao Informado.";
         $this->erro_campo = "k15_ageced";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_posjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_posjur"])){ 
       $sql  .= $virgula." k15_posjur = '$this->k15_posjur' ";
       $virgula = ",";
       if(trim($this->k15_posjur) == null ){ 
         $this->erro_sql = " Campo Posição Juros nao Informado.";
         $this->erro_campo = "k15_posjur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_posmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_posmul"])){ 
       $sql  .= $virgula." k15_posmul = '$this->k15_posmul' ";
       $virgula = ",";
     }
     if(trim($this->k15_taman)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_taman"])){ 
        if(trim($this->k15_taman)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k15_taman"])){ 
           $this->k15_taman = "0" ; 
        } 
       $sql  .= $virgula." k15_taman = $this->k15_taman ";
       $virgula = ",";
       if(trim($this->k15_taman) == null ){ 
         $this->erro_sql = " Campo Tamanho registro nao Informado.";
         $this->erro_campo = "k15_taman";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_posdta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_posdta"])){ 
       $sql  .= $virgula." k15_posdta = '$this->k15_posdta' ";
       $virgula = ",";
       if(trim($this->k15_posdta) == null ){ 
         $this->erro_sql = " Campo Posição Dia nao Informado.";
         $this->erro_campo = "k15_posdta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_numbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_numbco"])){ 
       $sql  .= $virgula." k15_numbco = '$this->k15_numbco' ";
       $virgula = ",";
       if(trim($this->k15_numbco) == null ){ 
         $this->erro_sql = " Campo Codigo do Banco nao Informado.";
         $this->erro_campo = "k15_numbco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_numpre"])){ 
       $sql  .= $virgula." k15_numpre = '$this->k15_numpre' ";
       $virgula = ",";
       if(trim($this->k15_numpre) == null ){ 
         $this->erro_sql = " Campo Posição do numpre no arquivo txt nao Informado.";
         $this->erro_campo = "k15_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_numpar"])){ 
       $sql  .= $virgula." k15_numpar = '$this->k15_numpar' ";
       $virgula = ",";
       if(trim($this->k15_numpar) == null ){ 
         $this->erro_sql = " Campo Posição do numpar no arquivo txt nao Informado.";
         $this->erro_campo = "k15_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_plmes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_plmes"])){ 
       $sql  .= $virgula." k15_plmes = '$this->k15_plmes' ";
       $virgula = ",";
       if(trim($this->k15_plmes) == null ){ 
         $this->erro_sql = " Campo Posicao Mes nao Informado.";
         $this->erro_campo = "k15_plmes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_plano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_plano"])){ 
       $sql  .= $virgula." k15_plano = '$this->k15_plano' ";
       $virgula = ",";
       if(trim($this->k15_plano) == null ){ 
         $this->erro_sql = " Campo Posicao Ano nao Informado.";
         $this->erro_campo = "k15_plano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_pdmes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_pdmes"])){ 
       $sql  .= $virgula." k15_pdmes = '$this->k15_pdmes' ";
       $virgula = ",";
       if(trim($this->k15_pdmes) == null ){ 
         $this->erro_sql = " Campo Posicao Mes nao Informado.";
         $this->erro_campo = "k15_pdmes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_pdano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_pdano"])){ 
       $sql  .= $virgula." k15_pdano = '$this->k15_pdano' ";
       $virgula = ",";
       if(trim($this->k15_pdano) == null ){ 
         $this->erro_sql = " Campo Posicao Ano nao Informado.";
         $this->erro_campo = "k15_pdano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_ppmes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_ppmes"])){ 
       $sql  .= $virgula." k15_ppmes = '$this->k15_ppmes' ";
       $virgula = ",";
       if(trim($this->k15_ppmes) == null ){ 
         $this->erro_sql = " Campo Posicao Mes nao Informado.";
         $this->erro_campo = "k15_ppmes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_ppano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_ppano"])){ 
       $sql  .= $virgula." k15_ppano = '$this->k15_ppano' ";
       $virgula = ",";
       if(trim($this->k15_ppano) == null ){ 
         $this->erro_sql = " Campo Posicao Ano nao Informado.";
         $this->erro_campo = "k15_ppano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  k15_codigo = $this->k15_codigo
";
     $resaco = $this->sql_record($this->sql_query_file($this->k15_codigo));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,4841,'$this->k15_codigo','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_codigo"]))
         $resac = pg_query("insert into db_acount values($acount,116,4841,'".pg_result($resaco,0,'k15_codigo')."','$this->k15_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_numcgm"]))
         $resac = pg_query("insert into db_acount values($acount,116,585,'".pg_result($resaco,0,'k15_numcgm')."','$this->k15_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_codbco"]))
         $resac = pg_query("insert into db_acount values($acount,116,586,'".pg_result($resaco,0,'k15_codbco')."','$this->k15_codbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_codage"]))
         $resac = pg_query("insert into db_acount values($acount,116,587,'".pg_result($resaco,0,'k15_codage')."','$this->k15_codage',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_contat"]))
         $resac = pg_query("insert into db_acount values($acount,116,588,'".pg_result($resaco,0,'k15_contat')."','$this->k15_contat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_gerent"]))
         $resac = pg_query("insert into db_acount values($acount,116,589,'".pg_result($resaco,0,'k15_gerent')."','$this->k15_gerent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_agenci"]))
         $resac = pg_query("insert into db_acount values($acount,116,590,'".pg_result($resaco,0,'k15_agenci')."','$this->k15_agenci',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_conv1"]))
         $resac = pg_query("insert into db_acount values($acount,116,595,'".pg_result($resaco,0,'k15_conv1')."','$this->k15_conv1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_conv2"]))
         $resac = pg_query("insert into db_acount values($acount,116,596,'".pg_result($resaco,0,'k15_conv2')."','$this->k15_conv2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_conv3"]))
         $resac = pg_query("insert into db_acount values($acount,116,597,'".pg_result($resaco,0,'k15_conv3')."','$this->k15_conv3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_conv4"]))
         $resac = pg_query("insert into db_acount values($acount,116,599,'".pg_result($resaco,0,'k15_conv4')."','$this->k15_conv4',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_conv5"]))
         $resac = pg_query("insert into db_acount values($acount,116,600,'".pg_result($resaco,0,'k15_conv5')."','$this->k15_conv5',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_seq1"]))
         $resac = pg_query("insert into db_acount values($acount,116,603,'".pg_result($resaco,0,'k15_seq1')."','$this->k15_seq1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_seq2"]))
         $resac = pg_query("insert into db_acount values($acount,116,604,'".pg_result($resaco,0,'k15_seq2')."','$this->k15_seq2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_seq3"]))
         $resac = pg_query("insert into db_acount values($acount,116,606,'".pg_result($resaco,0,'k15_seq3')."','$this->k15_seq3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_seq4"]))
         $resac = pg_query("insert into db_acount values($acount,116,607,'".pg_result($resaco,0,'k15_seq4')."','$this->k15_seq4',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_seq5"]))
         $resac = pg_query("insert into db_acount values($acount,116,608,'".pg_result($resaco,0,'k15_seq5')."','$this->k15_seq5',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_ceden1"]))
         $resac = pg_query("insert into db_acount values($acount,116,610,'".pg_result($resaco,0,'k15_ceden1')."','$this->k15_ceden1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_ceden2"]))
         $resac = pg_query("insert into db_acount values($acount,116,611,'".pg_result($resaco,0,'k15_ceden2')."','$this->k15_ceden2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_ceden3"]))
         $resac = pg_query("insert into db_acount values($acount,116,613,'".pg_result($resaco,0,'k15_ceden3')."','$this->k15_ceden3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_ceden4"]))
         $resac = pg_query("insert into db_acount values($acount,116,614,'".pg_result($resaco,0,'k15_ceden4')."','$this->k15_ceden4',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_ceden5"]))
         $resac = pg_query("insert into db_acount values($acount,116,616,'".pg_result($resaco,0,'k15_ceden5')."','$this->k15_ceden5',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_posbco"]))
         $resac = pg_query("insert into db_acount values($acount,116,617,'".pg_result($resaco,0,'k15_posbco')."','$this->k15_posbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_poslan"]))
         $resac = pg_query("insert into db_acount values($acount,116,619,'".pg_result($resaco,0,'k15_poslan')."','$this->k15_poslan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_pospag"]))
         $resac = pg_query("insert into db_acount values($acount,116,620,'".pg_result($resaco,0,'k15_pospag')."','$this->k15_pospag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_posvlr"]))
         $resac = pg_query("insert into db_acount values($acount,116,621,'".pg_result($resaco,0,'k15_posvlr')."','$this->k15_posvlr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_posacr"]))
         $resac = pg_query("insert into db_acount values($acount,116,622,'".pg_result($resaco,0,'k15_posacr')."','$this->k15_posacr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_posdes"]))
         $resac = pg_query("insert into db_acount values($acount,116,623,'".pg_result($resaco,0,'k15_posdes')."','$this->k15_posdes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_posced"]))
         $resac = pg_query("insert into db_acount values($acount,116,624,'".pg_result($resaco,0,'k15_posced')."','$this->k15_posced',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_poscon"]))
         $resac = pg_query("insert into db_acount values($acount,116,625,'".pg_result($resaco,0,'k15_poscon')."','$this->k15_poscon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_seq"]))
         $resac = pg_query("insert into db_acount values($acount,116,626,'".pg_result($resaco,0,'k15_seq')."','$this->k15_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_conta"]))
         $resac = pg_query("insert into db_acount values($acount,116,627,'".pg_result($resaco,0,'k15_conta')."','$this->k15_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_rectxb"]))
         $resac = pg_query("insert into db_acount values($acount,116,629,'".pg_result($resaco,0,'k15_rectxb')."','$this->k15_rectxb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_txban"]))
         $resac = pg_query("insert into db_acount values($acount,116,630,'".pg_result($resaco,0,'k15_txban')."','$this->k15_txban',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_local"]))
         $resac = pg_query("insert into db_acount values($acount,116,631,'".pg_result($resaco,0,'k15_local')."','$this->k15_local',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_carte"]))
         $resac = pg_query("insert into db_acount values($acount,116,632,'".pg_result($resaco,0,'k15_carte')."','$this->k15_carte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_espec"]))
         $resac = pg_query("insert into db_acount values($acount,116,633,'".pg_result($resaco,0,'k15_espec')."','$this->k15_espec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_aceite"]))
         $resac = pg_query("insert into db_acount values($acount,116,634,'".pg_result($resaco,0,'k15_aceite')."','$this->k15_aceite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_ageced"]))
         $resac = pg_query("insert into db_acount values($acount,116,635,'".pg_result($resaco,0,'k15_ageced')."','$this->k15_ageced',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_posjur"]))
         $resac = pg_query("insert into db_acount values($acount,116,1093,'".pg_result($resaco,0,'k15_posjur')."','$this->k15_posjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_posmul"]))
         $resac = pg_query("insert into db_acount values($acount,116,1094,'".pg_result($resaco,0,'k15_posmul')."','$this->k15_posmul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_taman"]))
         $resac = pg_query("insert into db_acount values($acount,116,1890,'".pg_result($resaco,0,'k15_taman')."','$this->k15_taman',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_posdta"]))
         $resac = pg_query("insert into db_acount values($acount,116,1894,'".pg_result($resaco,0,'k15_posdta')."','$this->k15_posdta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_numbco"]))
         $resac = pg_query("insert into db_acount values($acount,116,2385,'".pg_result($resaco,0,'k15_numbco')."','$this->k15_numbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_numpre"]))
         $resac = pg_query("insert into db_acount values($acount,116,4842,'".pg_result($resaco,0,'k15_numpre')."','$this->k15_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_numpar"]))
         $resac = pg_query("insert into db_acount values($acount,116,4843,'".pg_result($resaco,0,'k15_numpar')."','$this->k15_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_plmes"]))
         $resac = pg_query("insert into db_acount values($acount,116,2402,'".pg_result($resaco,0,'k15_plmes')."','$this->k15_plmes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_plano"]))
         $resac = pg_query("insert into db_acount values($acount,116,2403,'".pg_result($resaco,0,'k15_plano')."','$this->k15_plano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_pdmes"]))
         $resac = pg_query("insert into db_acount values($acount,116,2404,'".pg_result($resaco,0,'k15_pdmes')."','$this->k15_pdmes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_pdano"]))
         $resac = pg_query("insert into db_acount values($acount,116,2405,'".pg_result($resaco,0,'k15_pdano')."','$this->k15_pdano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_ppmes"]))
         $resac = pg_query("insert into db_acount values($acount,116,2406,'".pg_result($resaco,0,'k15_ppmes')."','$this->k15_ppmes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k15_ppano"]))
         $resac = pg_query("insert into db_acount values($acount,116,2407,'".pg_result($resaco,0,'k15_ppano')."','$this->k15_ppano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k15_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k15_codigo=null) { 
     $resaco = $this->sql_record($this->sql_query_file($k15_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,4841,'".pg_result($resaco,$iresaco,'k15_codigo')."','E')");
         $resac = pg_query("insert into db_acount values($acount,116,4841,'','".pg_result($resaco,$iresaco,'k15_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,585,'','".pg_result($resaco,$iresaco,'k15_numcgm')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,586,'','".pg_result($resaco,$iresaco,'k15_codbco')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,587,'','".pg_result($resaco,$iresaco,'k15_codage')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,588,'','".pg_result($resaco,$iresaco,'k15_contat')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,589,'','".pg_result($resaco,$iresaco,'k15_gerent')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,590,'','".pg_result($resaco,$iresaco,'k15_agenci')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,595,'','".pg_result($resaco,$iresaco,'k15_conv1')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,596,'','".pg_result($resaco,$iresaco,'k15_conv2')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,597,'','".pg_result($resaco,$iresaco,'k15_conv3')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,599,'','".pg_result($resaco,$iresaco,'k15_conv4')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,600,'','".pg_result($resaco,$iresaco,'k15_conv5')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,603,'','".pg_result($resaco,$iresaco,'k15_seq1')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,604,'','".pg_result($resaco,$iresaco,'k15_seq2')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,606,'','".pg_result($resaco,$iresaco,'k15_seq3')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,607,'','".pg_result($resaco,$iresaco,'k15_seq4')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,608,'','".pg_result($resaco,$iresaco,'k15_seq5')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,610,'','".pg_result($resaco,$iresaco,'k15_ceden1')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,611,'','".pg_result($resaco,$iresaco,'k15_ceden2')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,613,'','".pg_result($resaco,$iresaco,'k15_ceden3')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,614,'','".pg_result($resaco,$iresaco,'k15_ceden4')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,616,'','".pg_result($resaco,$iresaco,'k15_ceden5')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,617,'','".pg_result($resaco,$iresaco,'k15_posbco')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,619,'','".pg_result($resaco,$iresaco,'k15_poslan')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,620,'','".pg_result($resaco,$iresaco,'k15_pospag')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,621,'','".pg_result($resaco,$iresaco,'k15_posvlr')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,622,'','".pg_result($resaco,$iresaco,'k15_posacr')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,623,'','".pg_result($resaco,$iresaco,'k15_posdes')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,624,'','".pg_result($resaco,$iresaco,'k15_posced')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,625,'','".pg_result($resaco,$iresaco,'k15_poscon')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,626,'','".pg_result($resaco,$iresaco,'k15_seq')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,627,'','".pg_result($resaco,$iresaco,'k15_conta')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,629,'','".pg_result($resaco,$iresaco,'k15_rectxb')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,630,'','".pg_result($resaco,$iresaco,'k15_txban')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,631,'','".pg_result($resaco,$iresaco,'k15_local')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,632,'','".pg_result($resaco,$iresaco,'k15_carte')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,633,'','".pg_result($resaco,$iresaco,'k15_espec')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,634,'','".pg_result($resaco,$iresaco,'k15_aceite')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,635,'','".pg_result($resaco,$iresaco,'k15_ageced')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,1093,'','".pg_result($resaco,$iresaco,'k15_posjur')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,1094,'','".pg_result($resaco,$iresaco,'k15_posmul')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,1890,'','".pg_result($resaco,$iresaco,'k15_taman')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,1894,'','".pg_result($resaco,$iresaco,'k15_posdta')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,2385,'','".pg_result($resaco,$iresaco,'k15_numbco')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,4842,'','".pg_result($resaco,$iresaco,'k15_numpre')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,4843,'','".pg_result($resaco,$iresaco,'k15_numpar')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,2402,'','".pg_result($resaco,$iresaco,'k15_plmes')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,2403,'','".pg_result($resaco,$iresaco,'k15_plano')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,2404,'','".pg_result($resaco,$iresaco,'k15_pdmes')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,2405,'','".pg_result($resaco,$iresaco,'k15_pdano')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,2406,'','".pg_result($resaco,$iresaco,'k15_ppmes')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,116,2407,'','".pg_result($resaco,$iresaco,'k15_ppano')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadban
                    where ";
     $sql2 = "";
      if($k15_codigo != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " k15_codigo = $k15_codigo ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k15_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k15_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadban ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = cadban.k15_numcgm";
     $sql .= "      inner join bancos  on  bancos.codbco = cadban.k15_codbco";
     $sql2 = "";
     if($dbwhere==""){
       if($k15_codigo!=null ){
         $sql2 .= " where cadban.k15_codigo = $k15_codigo "; 
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
   function sql_query_file ( $k15_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadban ";
     $sql2 = "";
     if($dbwhere==""){
       if($k15_codigo!=null ){
         $sql2 .= " where cadban.k15_codigo = $k15_codigo "; 
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