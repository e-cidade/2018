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

//MODULO: protocolo
//CLASSE DA ENTIDADE cgmalt
class cl_cgmalt { 
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
   var $z05_sequencia = 0; 
   var $z05_ufcon = null; 
   var $z05_uf = null; 
   var $z05_tipcre = 0; 
   var $z05_telef = null; 
   var $z05_telcon = null; 
   var $z05_telcel = null; 
   var $z05_profis = null; 
   var $z05_numero = 0; 
   var $z05_numcon = 0; 
   var $z05_numcgm = 0; 
   var $z05_nome = null; 
   var $z05_nacion = 0; 
   var $z05_munic = null; 
   var $z05_muncon = null; 
   var $z05_login = 0; 
   var $z05_incest = null; 
   var $z05_ident = null; 
   var $z05_estciv = 0; 
   var $z05_ender = null; 
   var $z05_endcon = null; 
   var $z05_emailc = null; 
   var $z05_email = null; 
   var $z05_cxpostal = null; 
   var $z05_cxposcon = null; 
   var $z05_cpf = null; 
   var $z05_compl = null; 
   var $z05_comcon = null; 
   var $z05_cgccpf = null; 
   var $z05_cgc = null; 
   var $z05_cepcon = null; 
   var $z05_cep = null; 
   var $z05_celcon = null; 
   var $z05_cadast_dia = null; 
   var $z05_cadast_mes = null; 
   var $z05_cadast_ano = null; 
   var $z05_cadast = null; 
   var $z05_bairro = null; 
   var $z05_baicon = null; 
   var $z05_tipo_alt = null; 
   var $z05_hora = null; 
   var $z05_login_alt = 0; 
   var $z05_data_alt_dia = null; 
   var $z05_data_alt_mes = null; 
   var $z05_data_alt_ano = null; 
   var $z05_data_alt = null; 
   var $z05_hora_alt = null; 
   var $z05_ultalt_dia = null; 
   var $z05_ultalt_mes = null; 
   var $z05_ultalt_ano = null; 
   var $z05_ultalt = null; 
   var $z05_mae = null; 
   var $z05_pai = null; 
   var $z05_nomefanta = null; 
   var $z05_contato = null; 
   var $z05_sexo = null; 
   var $z05_nasc_dia = null; 
   var $z05_nasc_mes = null; 
   var $z05_nasc_ano = null; 
   var $z05_nasc = null; 
   var $z05_fax = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 z05_sequencia = int8 = Sequencial 
                 z05_ufcon = varchar(2) = Estado Comercial 
                 z05_uf = varchar(2) = UF 
                 z05_tipcre = int4 = Tipo de credor 
                 z05_telef = varchar(12) = Telefone 
                 z05_telcon = varchar(12) = Telefone comercial 
                 z05_telcel = varchar(12) = Celular 
                 z05_profis = varchar(40) = Profissao 
                 z05_numero = int4 = Numero 
                 z05_numcon = int4 = Numero 
                 z05_numcgm = int4 = Numcgm 
                 z05_nome = varchar(40) = Nome/Razão Social 
                 z05_nacion = int4 = Nacionalidade 
                 z05_munic = varchar(20) = Município 
                 z05_muncon = varchar(20) = Municipio Comercial 
                 z05_login = int4 = Login 
                 z05_incest = varchar(15) = Inscricao Estadual 
                 z05_ident = varchar(20) = Identidade 
                 z05_estciv = int4 = Estado civil 
                 z05_ender = varchar(100) = Endereço 
                 z05_endcon = varchar(100) = Endereco Comercial 
                 z05_emailc = varchar(100) = email comercial 
                 z05_email = varchar(100) = email 
                 z05_cxpostal = varchar(20) = Caixa Postal 
                 z05_cxposcon = varchar(20) = Caixa Postal 
                 z05_cpf = varchar(11) = CPF 
                 z05_compl = varchar(20) = Complemento 
                 z05_comcon = varchar(20) = Complemento 
                 z05_cgccpf = varchar(14) = CNPJ/CPF 
                 z05_cgc = varchar(14) = CNPJ (juridica) 
                 z05_cepcon = varchar(8) = CEP 
                 z05_cep = varchar(8) = CEP 
                 z05_celcon = varchar(12) = Celular comercial 
                 z05_cadast = date = Data do cadastramento 
                 z05_bairro = varchar(20) = Bairro 
                 z05_baicon = varchar(20) = Bairro 
                 z05_tipo_alt = varchar(1) = Tipo 
                 z05_hora = varchar(5) = Hora da Cadastramento 
                 z05_login_alt = int4 = Login 
                 z05_data_alt = date = Data 
                 z05_hora_alt = varchar(5) = Hora 
                 z05_ultalt = date = Ultima Alteração 
                 z05_mae = varchar(40) = Mãe 
                 z05_pai = varchar(40) = Pai 
                 z05_nomefanta = varchar(40) = Nome Fantasia 
                 z05_contato = varchar(40) = Contato 
                 z05_sexo = varchar(1) = Sexo 
                 z05_nasc = date = Data de Nasc. 
                 z05_fax = varchar(12) = Fax 
                 ";
   //funcao construtor da classe 
   function cl_cgmalt() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cgmalt"); 
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
       $this->z05_sequencia = ($this->z05_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_sequencia"]:$this->z05_sequencia);
       $this->z05_ufcon = ($this->z05_ufcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_ufcon"]:$this->z05_ufcon);
       $this->z05_uf = ($this->z05_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_uf"]:$this->z05_uf);
       $this->z05_tipcre = ($this->z05_tipcre == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_tipcre"]:$this->z05_tipcre);
       $this->z05_telef = ($this->z05_telef == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_telef"]:$this->z05_telef);
       $this->z05_telcon = ($this->z05_telcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_telcon"]:$this->z05_telcon);
       $this->z05_telcel = ($this->z05_telcel == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_telcel"]:$this->z05_telcel);
       $this->z05_profis = ($this->z05_profis == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_profis"]:$this->z05_profis);
       $this->z05_numero = ($this->z05_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_numero"]:$this->z05_numero);
       $this->z05_numcon = ($this->z05_numcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_numcon"]:$this->z05_numcon);
       $this->z05_numcgm = ($this->z05_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_numcgm"]:$this->z05_numcgm);
       $this->z05_nome = ($this->z05_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_nome"]:$this->z05_nome);
       $this->z05_nacion = ($this->z05_nacion == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_nacion"]:$this->z05_nacion);
       $this->z05_munic = ($this->z05_munic == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_munic"]:$this->z05_munic);
       $this->z05_muncon = ($this->z05_muncon == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_muncon"]:$this->z05_muncon);
       $this->z05_login = ($this->z05_login == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_login"]:$this->z05_login);
       $this->z05_incest = ($this->z05_incest == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_incest"]:$this->z05_incest);
       $this->z05_ident = ($this->z05_ident == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_ident"]:$this->z05_ident);
       $this->z05_estciv = ($this->z05_estciv == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_estciv"]:$this->z05_estciv);
       $this->z05_ender = ($this->z05_ender == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_ender"]:$this->z05_ender);
       $this->z05_endcon = ($this->z05_endcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_endcon"]:$this->z05_endcon);
       $this->z05_emailc = ($this->z05_emailc == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_emailc"]:$this->z05_emailc);
       $this->z05_email = ($this->z05_email == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_email"]:$this->z05_email);
       $this->z05_cxpostal = ($this->z05_cxpostal == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_cxpostal"]:$this->z05_cxpostal);
       $this->z05_cxposcon = ($this->z05_cxposcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_cxposcon"]:$this->z05_cxposcon);
       $this->z05_cpf = ($this->z05_cpf == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_cpf"]:$this->z05_cpf);
       $this->z05_compl = ($this->z05_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_compl"]:$this->z05_compl);
       $this->z05_comcon = ($this->z05_comcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_comcon"]:$this->z05_comcon);
       $this->z05_cgccpf = ($this->z05_cgccpf == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_cgccpf"]:$this->z05_cgccpf);
       $this->z05_cgc = ($this->z05_cgc == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_cgc"]:$this->z05_cgc);
       $this->z05_cepcon = ($this->z05_cepcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_cepcon"]:$this->z05_cepcon);
       $this->z05_cep = ($this->z05_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_cep"]:$this->z05_cep);
       $this->z05_celcon = ($this->z05_celcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_celcon"]:$this->z05_celcon);
       if($this->z05_cadast == ""){
         $this->z05_cadast_dia = ($this->z05_cadast_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_cadast_dia"]:$this->z05_cadast_dia);
         $this->z05_cadast_mes = ($this->z05_cadast_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_cadast_mes"]:$this->z05_cadast_mes);
         $this->z05_cadast_ano = ($this->z05_cadast_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_cadast_ano"]:$this->z05_cadast_ano);
         if($this->z05_cadast_dia != ""){
            $this->z05_cadast = $this->z05_cadast_ano."-".$this->z05_cadast_mes."-".$this->z05_cadast_dia;
         }
       }
       $this->z05_bairro = ($this->z05_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_bairro"]:$this->z05_bairro);
       $this->z05_baicon = ($this->z05_baicon == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_baicon"]:$this->z05_baicon);
       $this->z05_tipo_alt = ($this->z05_tipo_alt == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_tipo_alt"]:$this->z05_tipo_alt);
       $this->z05_hora = ($this->z05_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_hora"]:$this->z05_hora);
       $this->z05_login_alt = ($this->z05_login_alt == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_login_alt"]:$this->z05_login_alt);
       if($this->z05_data_alt == ""){
         $this->z05_data_alt_dia = ($this->z05_data_alt_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_data_alt_dia"]:$this->z05_data_alt_dia);
         $this->z05_data_alt_mes = ($this->z05_data_alt_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_data_alt_mes"]:$this->z05_data_alt_mes);
         $this->z05_data_alt_ano = ($this->z05_data_alt_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_data_alt_ano"]:$this->z05_data_alt_ano);
         if($this->z05_data_alt_dia != ""){
            $this->z05_data_alt = $this->z05_data_alt_ano."-".$this->z05_data_alt_mes."-".$this->z05_data_alt_dia;
         }
       }
       $this->z05_hora_alt = ($this->z05_hora_alt == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_hora_alt"]:$this->z05_hora_alt);
       if($this->z05_ultalt == ""){
         $this->z05_ultalt_dia = ($this->z05_ultalt_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_ultalt_dia"]:$this->z05_ultalt_dia);
         $this->z05_ultalt_mes = ($this->z05_ultalt_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_ultalt_mes"]:$this->z05_ultalt_mes);
         $this->z05_ultalt_ano = ($this->z05_ultalt_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_ultalt_ano"]:$this->z05_ultalt_ano);
         if($this->z05_ultalt_dia != ""){
            $this->z05_ultalt = $this->z05_ultalt_ano."-".$this->z05_ultalt_mes."-".$this->z05_ultalt_dia;
         }
       }
       $this->z05_mae = ($this->z05_mae == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_mae"]:$this->z05_mae);
       $this->z05_pai = ($this->z05_pai == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_pai"]:$this->z05_pai);
       $this->z05_nomefanta = ($this->z05_nomefanta == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_nomefanta"]:$this->z05_nomefanta);
       $this->z05_contato = ($this->z05_contato == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_contato"]:$this->z05_contato);
       $this->z05_sexo = ($this->z05_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_sexo"]:$this->z05_sexo);
       if($this->z05_nasc == ""){
         $this->z05_nasc_dia = ($this->z05_nasc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_nasc_dia"]:$this->z05_nasc_dia);
         $this->z05_nasc_mes = ($this->z05_nasc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_nasc_mes"]:$this->z05_nasc_mes);
         $this->z05_nasc_ano = ($this->z05_nasc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_nasc_ano"]:$this->z05_nasc_ano);
         if($this->z05_nasc_dia != ""){
            $this->z05_nasc = $this->z05_nasc_ano."-".$this->z05_nasc_mes."-".$this->z05_nasc_dia;
         }
       }
       $this->z05_fax = ($this->z05_fax == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_fax"]:$this->z05_fax);
     }else{
       $this->z05_sequencia = ($this->z05_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["z05_sequencia"]:$this->z05_sequencia);
     }
   }
   // funcao para inclusao
   function incluir ($z05_sequencia){ 
      $this->atualizacampos();
     if($this->z05_tipcre == null ){ 
       $this->z05_tipcre = "0";
     }
     if($this->z05_numero == null ){ 
       $this->z05_numero = "0";
     }
     if($this->z05_numcon == null ){ 
       $this->z05_numcon = "0";
     }
     if($this->z05_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "z05_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z05_nome == null ){ 
       $this->erro_sql = " Campo Nome/Razão Social nao Informado.";
       $this->erro_campo = "z05_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z05_nacion == null ){ 
       $this->z05_nacion = "0";
     }
     if($this->z05_login == null ){ 
       $this->z05_login = "0";
     }
     if($this->z05_estciv == null ){ 
       $this->z05_estciv = "0";
     }
     if($this->z05_ender == null ){ 
       $this->erro_sql = " Campo Endereço nao Informado.";
       $this->erro_campo = "z05_ender";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z05_cep == null ){ 
       $this->erro_sql = " Campo CEP nao Informado.";
       $this->erro_campo = "z05_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z05_cadast == null ){ 
       $this->z05_cadast = "null";
     }
     if($this->z05_tipo_alt == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "z05_tipo_alt";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z05_login_alt == null ){ 
       $this->z05_login_alt = "0";
     }
     if($this->z05_data_alt == null ){ 
       $this->z05_data_alt = "null";
     }
     if($this->z05_ultalt == null ){ 
       $this->z05_ultalt = "null";
     }
     if($this->z05_nasc == null ){ 
       $this->z05_nasc = "null";
     }
     if($z05_sequencia == "" || $z05_sequencia == null ){
       $result = db_query("select nextval('cgmalt_z05_sequencia_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cgmalt_z05_sequencia_seq do campo: z05_sequencia"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->z05_sequencia = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cgmalt_z05_sequencia_seq");
       if(($result != false) && (pg_result($result,0,0) < $z05_sequencia)){
         $this->erro_sql = " Campo z05_sequencia maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->z05_sequencia = $z05_sequencia; 
       }
     }
     if(($this->z05_sequencia == null) || ($this->z05_sequencia == "") ){ 
       $this->erro_sql = " Campo z05_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cgmalt(
                                       z05_sequencia 
                                      ,z05_ufcon 
                                      ,z05_uf 
                                      ,z05_tipcre 
                                      ,z05_telef 
                                      ,z05_telcon 
                                      ,z05_telcel 
                                      ,z05_profis 
                                      ,z05_numero 
                                      ,z05_numcon 
                                      ,z05_numcgm 
                                      ,z05_nome 
                                      ,z05_nacion 
                                      ,z05_munic 
                                      ,z05_muncon 
                                      ,z05_login 
                                      ,z05_incest 
                                      ,z05_ident 
                                      ,z05_estciv 
                                      ,z05_ender 
                                      ,z05_endcon 
                                      ,z05_emailc 
                                      ,z05_email 
                                      ,z05_cxpostal 
                                      ,z05_cxposcon 
                                      ,z05_cpf 
                                      ,z05_compl 
                                      ,z05_comcon 
                                      ,z05_cgccpf 
                                      ,z05_cgc 
                                      ,z05_cepcon 
                                      ,z05_cep 
                                      ,z05_celcon 
                                      ,z05_cadast 
                                      ,z05_bairro 
                                      ,z05_baicon 
                                      ,z05_tipo_alt 
                                      ,z05_hora 
                                      ,z05_login_alt 
                                      ,z05_data_alt 
                                      ,z05_hora_alt 
                                      ,z05_ultalt 
                                      ,z05_mae 
                                      ,z05_pai 
                                      ,z05_nomefanta 
                                      ,z05_contato 
                                      ,z05_sexo 
                                      ,z05_nasc 
                                      ,z05_fax 
                       )
                values (
                                $this->z05_sequencia 
                               ,'$this->z05_ufcon' 
                               ,'$this->z05_uf' 
                               ,$this->z05_tipcre 
                               ,'$this->z05_telef' 
                               ,'$this->z05_telcon' 
                               ,'$this->z05_telcel' 
                               ,'$this->z05_profis' 
                               ,$this->z05_numero 
                               ,$this->z05_numcon 
                               ,$this->z05_numcgm 
                               ,'$this->z05_nome' 
                               ,$this->z05_nacion 
                               ,'$this->z05_munic' 
                               ,'$this->z05_muncon' 
                               ,$this->z05_login 
                               ,'$this->z05_incest' 
                               ,'$this->z05_ident' 
                               ,$this->z05_estciv 
                               ,'$this->z05_ender' 
                               ,'$this->z05_endcon' 
                               ,'$this->z05_emailc' 
                               ,'$this->z05_email' 
                               ,'$this->z05_cxpostal' 
                               ,'$this->z05_cxposcon' 
                               ,'$this->z05_cpf' 
                               ,'$this->z05_compl' 
                               ,'$this->z05_comcon' 
                               ,'$this->z05_cgccpf' 
                               ,'$this->z05_cgc' 
                               ,'$this->z05_cepcon' 
                               ,'$this->z05_cep' 
                               ,'$this->z05_celcon' 
                               ,".($this->z05_cadast == "null" || $this->z05_cadast == ""?"null":"'".$this->z05_cadast."'")." 
                               ,'$this->z05_bairro' 
                               ,'$this->z05_baicon' 
                               ,'$this->z05_tipo_alt' 
                               ,'$this->z05_hora' 
                               ,$this->z05_login_alt 
                               ,".($this->z05_data_alt == "null" || $this->z05_data_alt == ""?"null":"'".$this->z05_data_alt."'")." 
                               ,'$this->z05_hora_alt' 
                               ,".($this->z05_ultalt == "null" || $this->z05_ultalt == ""?"null":"'".$this->z05_ultalt."'")." 
                               ,'$this->z05_mae' 
                               ,'$this->z05_pai' 
                               ,'$this->z05_nomefanta' 
                               ,'$this->z05_contato' 
                               ,'$this->z05_sexo' 
                               ,".($this->z05_nasc == "null" || $this->z05_nasc == ""?"null":"'".$this->z05_nasc."'")." 
                               ,'$this->z05_fax' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cgm's Alterados ou Excluidos ($this->z05_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cgm's Alterados ou Excluidos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cgm's Alterados ou Excluidos ($this->z05_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z05_sequencia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->z05_sequencia));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6758,'$this->z05_sequencia','I')");
       $resac = db_query("insert into db_acount values($acount,1102,6758,'','".AddSlashes(pg_result($resaco,0,'z05_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6729,'','".AddSlashes(pg_result($resaco,0,'z05_ufcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6728,'','".AddSlashes(pg_result($resaco,0,'z05_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6727,'','".AddSlashes(pg_result($resaco,0,'z05_tipcre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6726,'','".AddSlashes(pg_result($resaco,0,'z05_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6725,'','".AddSlashes(pg_result($resaco,0,'z05_telcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6724,'','".AddSlashes(pg_result($resaco,0,'z05_telcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6723,'','".AddSlashes(pg_result($resaco,0,'z05_profis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6722,'','".AddSlashes(pg_result($resaco,0,'z05_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6721,'','".AddSlashes(pg_result($resaco,0,'z05_numcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6720,'','".AddSlashes(pg_result($resaco,0,'z05_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6719,'','".AddSlashes(pg_result($resaco,0,'z05_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6718,'','".AddSlashes(pg_result($resaco,0,'z05_nacion'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6717,'','".AddSlashes(pg_result($resaco,0,'z05_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6716,'','".AddSlashes(pg_result($resaco,0,'z05_muncon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6715,'','".AddSlashes(pg_result($resaco,0,'z05_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6714,'','".AddSlashes(pg_result($resaco,0,'z05_incest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6713,'','".AddSlashes(pg_result($resaco,0,'z05_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6712,'','".AddSlashes(pg_result($resaco,0,'z05_estciv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6711,'','".AddSlashes(pg_result($resaco,0,'z05_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6710,'','".AddSlashes(pg_result($resaco,0,'z05_endcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6709,'','".AddSlashes(pg_result($resaco,0,'z05_emailc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6708,'','".AddSlashes(pg_result($resaco,0,'z05_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6707,'','".AddSlashes(pg_result($resaco,0,'z05_cxpostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6706,'','".AddSlashes(pg_result($resaco,0,'z05_cxposcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6705,'','".AddSlashes(pg_result($resaco,0,'z05_cpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6704,'','".AddSlashes(pg_result($resaco,0,'z05_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6703,'','".AddSlashes(pg_result($resaco,0,'z05_comcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6702,'','".AddSlashes(pg_result($resaco,0,'z05_cgccpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6701,'','".AddSlashes(pg_result($resaco,0,'z05_cgc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6700,'','".AddSlashes(pg_result($resaco,0,'z05_cepcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6699,'','".AddSlashes(pg_result($resaco,0,'z05_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6698,'','".AddSlashes(pg_result($resaco,0,'z05_celcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6697,'','".AddSlashes(pg_result($resaco,0,'z05_cadast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6696,'','".AddSlashes(pg_result($resaco,0,'z05_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6695,'','".AddSlashes(pg_result($resaco,0,'z05_baicon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6757,'','".AddSlashes(pg_result($resaco,0,'z05_tipo_alt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6756,'','".AddSlashes(pg_result($resaco,0,'z05_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6755,'','".AddSlashes(pg_result($resaco,0,'z05_login_alt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6754,'','".AddSlashes(pg_result($resaco,0,'z05_data_alt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6753,'','".AddSlashes(pg_result($resaco,0,'z05_hora_alt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6752,'','".AddSlashes(pg_result($resaco,0,'z05_ultalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6751,'','".AddSlashes(pg_result($resaco,0,'z05_mae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6750,'','".AddSlashes(pg_result($resaco,0,'z05_pai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6748,'','".AddSlashes(pg_result($resaco,0,'z05_nomefanta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6747,'','".AddSlashes(pg_result($resaco,0,'z05_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6746,'','".AddSlashes(pg_result($resaco,0,'z05_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6745,'','".AddSlashes(pg_result($resaco,0,'z05_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1102,6744,'','".AddSlashes(pg_result($resaco,0,'z05_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($z05_sequencia=null) { 
      $this->atualizacampos();
     $sql = " update cgmalt set ";
     $virgula = "";
     if(trim($this->z05_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_sequencia"])){ 
        if(trim($this->z05_sequencia)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z05_sequencia"])){ 
           $this->z05_sequencia = "0" ; 
        } 
       $sql  .= $virgula." z05_sequencia = $this->z05_sequencia ";
       $virgula = ",";
     }
     if(trim($this->z05_ufcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_ufcon"])){ 
       $sql  .= $virgula." z05_ufcon = '$this->z05_ufcon' ";
       $virgula = ",";
     }
     if(trim($this->z05_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_uf"])){ 
       $sql  .= $virgula." z05_uf = '$this->z05_uf' ";
       $virgula = ",";
     }
     if(trim($this->z05_tipcre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_tipcre"])){ 
        if(trim($this->z05_tipcre)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z05_tipcre"])){ 
           $this->z05_tipcre = "0" ; 
        } 
       $sql  .= $virgula." z05_tipcre = $this->z05_tipcre ";
       $virgula = ",";
     }
     if(trim($this->z05_telef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_telef"])){ 
       $sql  .= $virgula." z05_telef = '$this->z05_telef' ";
       $virgula = ",";
     }
     if(trim($this->z05_telcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_telcon"])){ 
       $sql  .= $virgula." z05_telcon = '$this->z05_telcon' ";
       $virgula = ",";
     }
     if(trim($this->z05_telcel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_telcel"])){ 
       $sql  .= $virgula." z05_telcel = '$this->z05_telcel' ";
       $virgula = ",";
     }
     if(trim($this->z05_profis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_profis"])){ 
       $sql  .= $virgula." z05_profis = '$this->z05_profis' ";
       $virgula = ",";
     }
     if(trim($this->z05_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_numero"])){ 
        if(trim($this->z05_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z05_numero"])){ 
           $this->z05_numero = "0" ; 
        } 
       $sql  .= $virgula." z05_numero = $this->z05_numero ";
       $virgula = ",";
     }
     if(trim($this->z05_numcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_numcon"])){ 
        if(trim($this->z05_numcon)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z05_numcon"])){ 
           $this->z05_numcon = "0" ; 
        } 
       $sql  .= $virgula." z05_numcon = $this->z05_numcon ";
       $virgula = ",";
     }
     if(trim($this->z05_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_numcgm"])){ 
       $sql  .= $virgula." z05_numcgm = $this->z05_numcgm ";
       $virgula = ",";
       if(trim($this->z05_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "z05_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z05_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_nome"])){ 
       $sql  .= $virgula." z05_nome = '$this->z05_nome' ";
       $virgula = ",";
       if(trim($this->z05_nome) == null ){ 
         $this->erro_sql = " Campo Nome/Razão Social nao Informado.";
         $this->erro_campo = "z05_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z05_nacion)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_nacion"])){ 
        if(trim($this->z05_nacion)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z05_nacion"])){ 
           $this->z05_nacion = "0" ; 
        } 
       $sql  .= $virgula." z05_nacion = $this->z05_nacion ";
       $virgula = ",";
     }
     if(trim($this->z05_munic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_munic"])){ 
       $sql  .= $virgula." z05_munic = '$this->z05_munic' ";
       $virgula = ",";
     }
     if(trim($this->z05_muncon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_muncon"])){ 
       $sql  .= $virgula." z05_muncon = '$this->z05_muncon' ";
       $virgula = ",";
     }
     if(trim($this->z05_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_login"])){ 
        if(trim($this->z05_login)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z05_login"])){ 
           $this->z05_login = "0" ; 
        } 
       $sql  .= $virgula." z05_login = $this->z05_login ";
       $virgula = ",";
     }
     if(trim($this->z05_incest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_incest"])){ 
       $sql  .= $virgula." z05_incest = '$this->z05_incest' ";
       $virgula = ",";
     }
     if(trim($this->z05_ident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_ident"])){ 
       $sql  .= $virgula." z05_ident = '$this->z05_ident' ";
       $virgula = ",";
     }
     if(trim($this->z05_estciv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_estciv"])){ 
        if(trim($this->z05_estciv)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z05_estciv"])){ 
           $this->z05_estciv = "0" ; 
        } 
       $sql  .= $virgula." z05_estciv = $this->z05_estciv ";
       $virgula = ",";
     }
     if(trim($this->z05_ender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_ender"])){ 
       $sql  .= $virgula." z05_ender = '$this->z05_ender' ";
       $virgula = ",";
       if(trim($this->z05_ender) == null ){ 
         $this->erro_sql = " Campo Endereço nao Informado.";
         $this->erro_campo = "z05_ender";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z05_endcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_endcon"])){ 
       $sql  .= $virgula." z05_endcon = '$this->z05_endcon' ";
       $virgula = ",";
     }
     if(trim($this->z05_emailc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_emailc"])){ 
       $sql  .= $virgula." z05_emailc = '$this->z05_emailc' ";
       $virgula = ",";
     }
     if(trim($this->z05_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_email"])){ 
       $sql  .= $virgula." z05_email = '$this->z05_email' ";
       $virgula = ",";
     }
     if(trim($this->z05_cxpostal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_cxpostal"])){ 
       $sql  .= $virgula." z05_cxpostal = '$this->z05_cxpostal' ";
       $virgula = ",";
     }
     if(trim($this->z05_cxposcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_cxposcon"])){ 
       $sql  .= $virgula." z05_cxposcon = '$this->z05_cxposcon' ";
       $virgula = ",";
     }
     if(trim($this->z05_cpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_cpf"])){ 
       $sql  .= $virgula." z05_cpf = '$this->z05_cpf' ";
       $virgula = ",";
     }
     if(trim($this->z05_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_compl"])){ 
       $sql  .= $virgula." z05_compl = '$this->z05_compl' ";
       $virgula = ",";
     }
     if(trim($this->z05_comcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_comcon"])){ 
       $sql  .= $virgula." z05_comcon = '$this->z05_comcon' ";
       $virgula = ",";
     }
     if(trim($this->z05_cgccpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_cgccpf"])){ 
       $sql  .= $virgula." z05_cgccpf = '$this->z05_cgccpf' ";
       $virgula = ",";
     }
     if(trim($this->z05_cgc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_cgc"])){ 
       $sql  .= $virgula." z05_cgc = '$this->z05_cgc' ";
       $virgula = ",";
     }
     if(trim($this->z05_cepcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_cepcon"])){ 
       $sql  .= $virgula." z05_cepcon = '$this->z05_cepcon' ";
       $virgula = ",";
     }
     if(trim($this->z05_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_cep"])){ 
       $sql  .= $virgula." z05_cep = '$this->z05_cep' ";
       $virgula = ",";
       if(trim($this->z05_cep) == null ){ 
         $this->erro_sql = " Campo CEP nao Informado.";
         $this->erro_campo = "z05_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z05_celcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_celcon"])){ 
       $sql  .= $virgula." z05_celcon = '$this->z05_celcon' ";
       $virgula = ",";
     }
     if(trim($this->z05_cadast)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_cadast_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z05_cadast_dia"] !="") ){ 
       $sql  .= $virgula." z05_cadast = '$this->z05_cadast' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z05_cadast_dia"])){ 
         $sql  .= $virgula." z05_cadast = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z05_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_bairro"])){ 
       $sql  .= $virgula." z05_bairro = '$this->z05_bairro' ";
       $virgula = ",";
     }
     if(trim($this->z05_baicon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_baicon"])){ 
       $sql  .= $virgula." z05_baicon = '$this->z05_baicon' ";
       $virgula = ",";
     }
     if(trim($this->z05_tipo_alt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_tipo_alt"])){ 
       $sql  .= $virgula." z05_tipo_alt = '$this->z05_tipo_alt' ";
       $virgula = ",";
       if(trim($this->z05_tipo_alt) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "z05_tipo_alt";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z05_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_hora"])){ 
       $sql  .= $virgula." z05_hora = '$this->z05_hora' ";
       $virgula = ",";
     }
     if(trim($this->z05_login_alt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_login_alt"])){ 
        if(trim($this->z05_login_alt)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z05_login_alt"])){ 
           $this->z05_login_alt = "0" ; 
        } 
       $sql  .= $virgula." z05_login_alt = $this->z05_login_alt ";
       $virgula = ",";
     }
     if(trim($this->z05_data_alt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_data_alt_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z05_data_alt_dia"] !="") ){ 
       $sql  .= $virgula." z05_data_alt = '$this->z05_data_alt' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z05_data_alt_dia"])){ 
         $sql  .= $virgula." z05_data_alt = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z05_hora_alt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_hora_alt"])){ 
       $sql  .= $virgula." z05_hora_alt = '$this->z05_hora_alt' ";
       $virgula = ",";
     }
     if(trim($this->z05_ultalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_ultalt_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z05_ultalt_dia"] !="") ){ 
       $sql  .= $virgula." z05_ultalt = '$this->z05_ultalt' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z05_ultalt_dia"])){ 
         $sql  .= $virgula." z05_ultalt = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z05_mae)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_mae"])){ 
       $sql  .= $virgula." z05_mae = '$this->z05_mae' ";
       $virgula = ",";
     }
     if(trim($this->z05_pai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_pai"])){ 
       $sql  .= $virgula." z05_pai = '$this->z05_pai' ";
       $virgula = ",";
     }
     if(trim($this->z05_nomefanta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_nomefanta"])){ 
       $sql  .= $virgula." z05_nomefanta = '$this->z05_nomefanta' ";
       $virgula = ",";
     }
     if(trim($this->z05_contato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_contato"])){ 
       $sql  .= $virgula." z05_contato = '$this->z05_contato' ";
       $virgula = ",";
     }
     if(trim($this->z05_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_sexo"])){ 
       $sql  .= $virgula." z05_sexo = '$this->z05_sexo' ";
       $virgula = ",";
     }
     if(trim($this->z05_nasc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_nasc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z05_nasc_dia"] !="") ){ 
       $sql  .= $virgula." z05_nasc = '$this->z05_nasc' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z05_nasc_dia"])){ 
         $sql  .= $virgula." z05_nasc = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z05_fax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z05_fax"])){ 
       $sql  .= $virgula." z05_fax = '$this->z05_fax' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($z05_sequencia!=null){
       $sql .= " z05_sequencia = $this->z05_sequencia";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->z05_sequencia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6758,'$this->z05_sequencia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1102,6758,'".AddSlashes(pg_result($resaco,$conresaco,'z05_sequencia'))."','$this->z05_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_ufcon"]))
           $resac = db_query("insert into db_acount values($acount,1102,6729,'".AddSlashes(pg_result($resaco,$conresaco,'z05_ufcon'))."','$this->z05_ufcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_uf"]))
           $resac = db_query("insert into db_acount values($acount,1102,6728,'".AddSlashes(pg_result($resaco,$conresaco,'z05_uf'))."','$this->z05_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_tipcre"]))
           $resac = db_query("insert into db_acount values($acount,1102,6727,'".AddSlashes(pg_result($resaco,$conresaco,'z05_tipcre'))."','$this->z05_tipcre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_telef"]))
           $resac = db_query("insert into db_acount values($acount,1102,6726,'".AddSlashes(pg_result($resaco,$conresaco,'z05_telef'))."','$this->z05_telef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_telcon"]))
           $resac = db_query("insert into db_acount values($acount,1102,6725,'".AddSlashes(pg_result($resaco,$conresaco,'z05_telcon'))."','$this->z05_telcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_telcel"]))
           $resac = db_query("insert into db_acount values($acount,1102,6724,'".AddSlashes(pg_result($resaco,$conresaco,'z05_telcel'))."','$this->z05_telcel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_profis"]))
           $resac = db_query("insert into db_acount values($acount,1102,6723,'".AddSlashes(pg_result($resaco,$conresaco,'z05_profis'))."','$this->z05_profis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_numero"]))
           $resac = db_query("insert into db_acount values($acount,1102,6722,'".AddSlashes(pg_result($resaco,$conresaco,'z05_numero'))."','$this->z05_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_numcon"]))
           $resac = db_query("insert into db_acount values($acount,1102,6721,'".AddSlashes(pg_result($resaco,$conresaco,'z05_numcon'))."','$this->z05_numcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,1102,6720,'".AddSlashes(pg_result($resaco,$conresaco,'z05_numcgm'))."','$this->z05_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_nome"]))
           $resac = db_query("insert into db_acount values($acount,1102,6719,'".AddSlashes(pg_result($resaco,$conresaco,'z05_nome'))."','$this->z05_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_nacion"]))
           $resac = db_query("insert into db_acount values($acount,1102,6718,'".AddSlashes(pg_result($resaco,$conresaco,'z05_nacion'))."','$this->z05_nacion',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_munic"]))
           $resac = db_query("insert into db_acount values($acount,1102,6717,'".AddSlashes(pg_result($resaco,$conresaco,'z05_munic'))."','$this->z05_munic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_muncon"]))
           $resac = db_query("insert into db_acount values($acount,1102,6716,'".AddSlashes(pg_result($resaco,$conresaco,'z05_muncon'))."','$this->z05_muncon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_login"]))
           $resac = db_query("insert into db_acount values($acount,1102,6715,'".AddSlashes(pg_result($resaco,$conresaco,'z05_login'))."','$this->z05_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_incest"]))
           $resac = db_query("insert into db_acount values($acount,1102,6714,'".AddSlashes(pg_result($resaco,$conresaco,'z05_incest'))."','$this->z05_incest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_ident"]))
           $resac = db_query("insert into db_acount values($acount,1102,6713,'".AddSlashes(pg_result($resaco,$conresaco,'z05_ident'))."','$this->z05_ident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_estciv"]))
           $resac = db_query("insert into db_acount values($acount,1102,6712,'".AddSlashes(pg_result($resaco,$conresaco,'z05_estciv'))."','$this->z05_estciv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_ender"]))
           $resac = db_query("insert into db_acount values($acount,1102,6711,'".AddSlashes(pg_result($resaco,$conresaco,'z05_ender'))."','$this->z05_ender',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_endcon"]))
           $resac = db_query("insert into db_acount values($acount,1102,6710,'".AddSlashes(pg_result($resaco,$conresaco,'z05_endcon'))."','$this->z05_endcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_emailc"]))
           $resac = db_query("insert into db_acount values($acount,1102,6709,'".AddSlashes(pg_result($resaco,$conresaco,'z05_emailc'))."','$this->z05_emailc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_email"]))
           $resac = db_query("insert into db_acount values($acount,1102,6708,'".AddSlashes(pg_result($resaco,$conresaco,'z05_email'))."','$this->z05_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_cxpostal"]))
           $resac = db_query("insert into db_acount values($acount,1102,6707,'".AddSlashes(pg_result($resaco,$conresaco,'z05_cxpostal'))."','$this->z05_cxpostal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_cxposcon"]))
           $resac = db_query("insert into db_acount values($acount,1102,6706,'".AddSlashes(pg_result($resaco,$conresaco,'z05_cxposcon'))."','$this->z05_cxposcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_cpf"]))
           $resac = db_query("insert into db_acount values($acount,1102,6705,'".AddSlashes(pg_result($resaco,$conresaco,'z05_cpf'))."','$this->z05_cpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_compl"]))
           $resac = db_query("insert into db_acount values($acount,1102,6704,'".AddSlashes(pg_result($resaco,$conresaco,'z05_compl'))."','$this->z05_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_comcon"]))
           $resac = db_query("insert into db_acount values($acount,1102,6703,'".AddSlashes(pg_result($resaco,$conresaco,'z05_comcon'))."','$this->z05_comcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_cgccpf"]))
           $resac = db_query("insert into db_acount values($acount,1102,6702,'".AddSlashes(pg_result($resaco,$conresaco,'z05_cgccpf'))."','$this->z05_cgccpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_cgc"]))
           $resac = db_query("insert into db_acount values($acount,1102,6701,'".AddSlashes(pg_result($resaco,$conresaco,'z05_cgc'))."','$this->z05_cgc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_cepcon"]))
           $resac = db_query("insert into db_acount values($acount,1102,6700,'".AddSlashes(pg_result($resaco,$conresaco,'z05_cepcon'))."','$this->z05_cepcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_cep"]))
           $resac = db_query("insert into db_acount values($acount,1102,6699,'".AddSlashes(pg_result($resaco,$conresaco,'z05_cep'))."','$this->z05_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_celcon"]))
           $resac = db_query("insert into db_acount values($acount,1102,6698,'".AddSlashes(pg_result($resaco,$conresaco,'z05_celcon'))."','$this->z05_celcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_cadast"]))
           $resac = db_query("insert into db_acount values($acount,1102,6697,'".AddSlashes(pg_result($resaco,$conresaco,'z05_cadast'))."','$this->z05_cadast',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_bairro"]))
           $resac = db_query("insert into db_acount values($acount,1102,6696,'".AddSlashes(pg_result($resaco,$conresaco,'z05_bairro'))."','$this->z05_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_baicon"]))
           $resac = db_query("insert into db_acount values($acount,1102,6695,'".AddSlashes(pg_result($resaco,$conresaco,'z05_baicon'))."','$this->z05_baicon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_tipo_alt"]))
           $resac = db_query("insert into db_acount values($acount,1102,6757,'".AddSlashes(pg_result($resaco,$conresaco,'z05_tipo_alt'))."','$this->z05_tipo_alt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_hora"]))
           $resac = db_query("insert into db_acount values($acount,1102,6756,'".AddSlashes(pg_result($resaco,$conresaco,'z05_hora'))."','$this->z05_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_login_alt"]))
           $resac = db_query("insert into db_acount values($acount,1102,6755,'".AddSlashes(pg_result($resaco,$conresaco,'z05_login_alt'))."','$this->z05_login_alt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_data_alt"]))
           $resac = db_query("insert into db_acount values($acount,1102,6754,'".AddSlashes(pg_result($resaco,$conresaco,'z05_data_alt'))."','$this->z05_data_alt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_hora_alt"]))
           $resac = db_query("insert into db_acount values($acount,1102,6753,'".AddSlashes(pg_result($resaco,$conresaco,'z05_hora_alt'))."','$this->z05_hora_alt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_ultalt"]))
           $resac = db_query("insert into db_acount values($acount,1102,6752,'".AddSlashes(pg_result($resaco,$conresaco,'z05_ultalt'))."','$this->z05_ultalt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_mae"]))
           $resac = db_query("insert into db_acount values($acount,1102,6751,'".AddSlashes(pg_result($resaco,$conresaco,'z05_mae'))."','$this->z05_mae',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_pai"]))
           $resac = db_query("insert into db_acount values($acount,1102,6750,'".AddSlashes(pg_result($resaco,$conresaco,'z05_pai'))."','$this->z05_pai',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_nomefanta"]))
           $resac = db_query("insert into db_acount values($acount,1102,6748,'".AddSlashes(pg_result($resaco,$conresaco,'z05_nomefanta'))."','$this->z05_nomefanta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_contato"]))
           $resac = db_query("insert into db_acount values($acount,1102,6747,'".AddSlashes(pg_result($resaco,$conresaco,'z05_contato'))."','$this->z05_contato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_sexo"]))
           $resac = db_query("insert into db_acount values($acount,1102,6746,'".AddSlashes(pg_result($resaco,$conresaco,'z05_sexo'))."','$this->z05_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_nasc"]))
           $resac = db_query("insert into db_acount values($acount,1102,6745,'".AddSlashes(pg_result($resaco,$conresaco,'z05_nasc'))."','$this->z05_nasc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z05_fax"]))
           $resac = db_query("insert into db_acount values($acount,1102,6744,'".AddSlashes(pg_result($resaco,$conresaco,'z05_fax'))."','$this->z05_fax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cgm's Alterados ou Excluidos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->z05_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cgm's Alterados ou Excluidos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->z05_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z05_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($z05_sequencia=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($z05_sequencia));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6758,'$z05_sequencia','E')");
         $resac = db_query("insert into db_acount values($acount,1102,6758,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6729,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_ufcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6728,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6727,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_tipcre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6726,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6725,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_telcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6724,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_telcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6723,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_profis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6722,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6721,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_numcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6720,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6719,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6718,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_nacion'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6717,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6716,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_muncon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6715,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6714,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_incest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6713,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6712,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_estciv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6711,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6710,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_endcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6709,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_emailc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6708,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6707,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_cxpostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6706,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_cxposcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6705,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_cpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6704,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6703,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_comcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6702,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_cgccpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6701,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_cgc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6700,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_cepcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6699,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6698,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_celcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6697,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_cadast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6696,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6695,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_baicon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6757,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_tipo_alt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6756,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6755,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_login_alt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6754,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_data_alt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6753,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_hora_alt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6752,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_ultalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6751,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_mae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6750,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_pai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6748,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_nomefanta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6747,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6746,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6745,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1102,6744,'','".AddSlashes(pg_result($resaco,$iresaco,'z05_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cgmalt
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($z05_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " z05_sequencia = $z05_sequencia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cgm's Alterados ou Excluidos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$z05_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cgm's Alterados ou Excluidos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$z05_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$z05_sequencia;
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
        $this->erro_sql   = "Record Vazio na Tabela:cgmalt";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $z05_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cgmalt ";
     $sql .= "      inner join db_usuarios on db_usuarios.id_usuario  = cgmalt.z05_login_alt";
     $sql2 = "";
     if($dbwhere==""){
       if($z05_sequencia!=null ){
         $sql2 .= " where cgmalt.z05_sequencia = $z05_sequencia "; 
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
   function sql_query_file ( $z05_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cgmalt ";
     $sql2 = "";
     if($dbwhere==""){
       if($z05_sequencia!=null ){
         $sql2 .= " where cgmalt.z05_sequencia = $z05_sequencia "; 
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